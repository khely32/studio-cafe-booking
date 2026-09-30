<?php

namespace App\Http\Controllers;

use App\Mail\BookingConfirmation;
use App\Models\Booking;
use App\Models\Service;
use App\Support\StudioSchedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;

class AdminController extends Controller
{
    public function dashboard()
    {
        $today = Carbon::today();
        $startOfWeek = Carbon::now()->startOfWeek();
        $startOfMonth = Carbon::now()->startOfMonth();

        $user = Auth::user();
        $hour = Carbon::now()->hour;
        if ($hour < 12) $greeting = 'Good morning';
        elseif ($hour < 17) $greeting = 'Good afternoon';
        else $greeting = 'Good evening';

        $upcomingBookings = Booking::with('service')
            ->where('booking_date', '>=', $today)
            ->whereIn('status', ['pending', 'confirmed'])
            ->orderBy('booking_date')
            ->orderBy('booking_time')
            ->limit(5)
            ->get();

        $pages = \App\Models\Page::where('is_published', true)->get();

        return view('admin.dashboard', compact('user', 'greeting', 'upcomingBookings', 'pages'));
    }

    public function bookings(Request $request)
    {
        $query = Booking::with('service');

        $filter = $request->get('filter', 'upcoming');
        $hasDateRange = $request->filled('date_from') || $request->filled('date_to');

        if ($hasDateRange) {
            // Cast to Carbon so the bound value is a full datetime; a bare "Y-m-d"
            // string never compares correctly against the stored "Y-m-d H:i:s" value.
            if ($request->filled('date_from')) {
                $query->where('booking_date', '>=', Carbon::parse($request->date_from)->startOfDay());
            }
            if ($request->filled('date_to')) {
                $query->where('booking_date', '<=', Carbon::parse($request->date_to)->endOfDay());
            }
        } else {
            if ($filter === 'upcoming') {
                $query->where('booking_date', '>=', Carbon::today());
            } elseif ($filter === 'past') {
                $query->where('booking_date', '<', Carbon::today());
            }
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('customer_name', 'like', "%{$search}%")
                  ->orWhere('customer_email', 'like', "%{$search}%")
                  ->orWhere('booking_ref', 'like', "%{$search}%");
            });
        }

        $bookings = $query->orderBy('booking_date', 'asc')
            ->orderBy('booking_time', 'asc')
            ->paginate(10)
            ->withQueryString();

        $totalUpcoming = Booking::where('booking_date', '>=', Carbon::today())->count();
        $totalPast = Booking::where('booking_date', '<', Carbon::today())->count();

        return view('admin.bookings', compact('bookings', 'filter', 'totalUpcoming', 'totalPast'));
    }

    public function updateStatus(Booking $booking, Request $request)
    {
        $request->merge(['status' => strtolower(trim((string) $request->input('status')))]);

        $validated = $request->validate([
            'status' => 'required|in:accepted,undecided,cancelled,pending,confirmed,completed,no_show',
        ]);

        $stored = match ($validated['status']) {
            'accepted' => 'confirmed',
            'undecided' => 'pending',
            default => $validated['status'],
        };

        $booking->update(['status' => $stored]);

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Status updated successfully',
                'data' => [
                    'id' => $booking->id,
                    'status' => $stored,
                ],
            ], 200);
        }

        return redirect()->back()->with('success', 'Booking status updated.');
    }

    public function bookingDetail(Booking $booking)
    {
        $booking->load('service', 'addons');
        return view('admin.detail', compact('booking'));
    }

    /**
     * Resend the booking confirmation email for a single booking.
     *
     * Sent synchronously (not queued) so the admin gets a truthful success or
     * failure back. The "log" mail driver is reported as a failure rather than a
     * silent success, because it writes to the log instead of delivering.
     */
    public function resendNotifications(Booking $booking)
    {
        $booking->loadMissing('service');

        $recipient = trim((string) $booking->customer_email);

        if ($recipient === '' || ! filter_var($recipient, FILTER_VALIDATE_EMAIL)) {
            return response()->json([
                'success' => false,
                'message' => $recipient === ''
                    ? 'This booking has no email address on file.'
                    : "This booking has an invalid email address ({$recipient}).",
            ], 422);
        }

        $mailer = config('mail.default');

        if ($mailer === 'log' || $mailer === 'array') {
            return response()->json([
                'success' => false,
                'message' => "Mail is not configured for delivery (MAIL_MAILER={$mailer}). Emails are only written to the log.",
                'mailer' => $mailer,
            ], 503);
        }

        try {
            Mail::to($recipient)->send(new BookingConfirmation($booking));
        } catch (\Throwable $e) {
            report($e);

            Log::error("Resend failed for {$booking->booking_ref} <{$recipient}> via {$mailer}: " . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Could not send the email: ' . $e->getMessage(),
                'mailer' => $mailer,
            ], 502);
        }

        Log::info("Resent booking confirmation for {$booking->booking_ref} to <{$recipient}> via {$mailer}");

        return response()->json([
            'success' => true,
            'message' => "Confirmation email sent to {$recipient}.",
            'data' => [
                'id' => $booking->id,
                'ref' => $booking->booking_ref,
                'email' => $recipient,
                'mailer' => $mailer,
                'sent_at' => now()->toDateTimeString(),
            ],
        ]);
    }

    public function bookingSlots(Booking $booking, Request $request)
    {
        $booking->loadMissing('service');

        $validated = $request->validate([
            'date' => 'required|date|after_or_equal:today',
        ]);

        $date = Carbon::parse($validated['date'])->format('Y-m-d');

        if (StudioSchedule::isClosed($date)) {
            return response()->json([
                'slots' => [],
                'message' => 'The studio is closed on Sundays.',
            ]);
        }

        return response()->json([
            'slots' => StudioSchedule::slotsFor($booking->service, $date, $booking->id),
            'studio_hours' => StudioSchedule::hoursLabel($date),
            'day_label' => Carbon::parse($date)->format('l'),
            'current_date' => $booking->booking_date->format('Y-m-d'),
            'current_time' => Carbon::parse($booking->booking_time)->format('H:i'),
        ]);
    }

    public function reschedule(Booking $booking, Request $request)
    {
        $booking->loadMissing('service');

        $validated = $request->validate([
            'booking_date' => 'required|date|after_or_equal:today',
            'booking_time' => 'required|date_format:H:i',
        ]);

        $date = Carbon::parse($validated['booking_date'])->format('Y-m-d');
        $time = $validated['booking_time'];

        if (StudioSchedule::isClosed($date)) {
            return $this->rescheduleFailure('The studio is closed on Sundays.', 422);
        }

        $slots = collect(StudioSchedule::slotsFor($booking->service, $date, $booking->id));

        $slot = $slots->firstWhere('time', $time);

        if ($slot === null) {
            return $this->rescheduleFailure('That time is outside studio hours for this service.', 422);
        }

        if (! $slot['available']) {
            return $this->rescheduleFailure('That slot was just taken. Please pick another time.', 409);
        }

        if ($date === Carbon::today()->format('Y-m-d')) {
            $startsAt = Carbon::parse("{$date} {$time}");

            if ($startsAt->lte(Carbon::now())) {
                return $this->rescheduleFailure('That time has already passed today.', 422);
            }
        }

        $unchanged = $booking->booking_date->format('Y-m-d') === $date
            && Carbon::parse($booking->booking_time)->format('H:i') === $time;

        if ($unchanged) {
            return response()->json([
                'success' => true,
                'message' => 'This booking is already on that date and time.',
                'data' => $this->reschedulePayload($booking),
            ]);
        }

        $booking->update([
            'booking_date' => $date,
            'booking_time' => $time,
        ]);

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Booking rescheduled.',
                'data' => $this->reschedulePayload($booking->fresh()),
            ]);
        }

        return redirect()->route('admin.bookings')->with('success', 'Booking rescheduled.');
    }

    private function reschedulePayload(Booking $booking): array
    {
        $duration = $booking->service->duration_minutes ?? 30;
        $start = Carbon::parse($booking->booking_time);
        $end = $start->copy()->addMinutes($duration);

        return [
            'id' => $booking->id,
            'booking_date' => $booking->booking_date->format('Y-m-d'),
            'date_label' => $booking->booking_date->format('D M j, Y'),
            'date_full' => $booking->booking_date->format('l, F jS, Y'),
            'start_time' => $start->format('g:i A'),
            'end_time' => $end->format('g:i A'),
            'start_iso' => $booking->booking_date->format('M j, Y') . ', ' . $start->format('g:i A'),
            'end_iso' => $booking->booking_date->format('M j, Y') . ', ' . $end->format('g:i A'),
        ];
    }

    private function rescheduleFailure(string $message, int $status)
    {
        if (request()->expectsJson()) {
            return response()->json(['success' => false, 'message' => $message], $status);
        }

        return back()->with('error', $message);
    }

    public function updateNote(Booking $booking, Request $request)
    {
        $request->validate([
            'internal_notes' => 'nullable|string|max:5000',
        ]);

        $booking->update(['internal_notes' => $request->internal_notes ?? '']);

        if ($request->expectsJson()) {
            return response()->json(['success' => true]);
        }

        return redirect()->back()->with('success', 'Note saved.');
    }

    public function destroy(Booking $booking)
    {
        $booking->delete();
        return redirect()->route('admin.bookings')->with('success', 'Booking deleted.');
    }

    public function saveSlack(Request $request)
    {
        $request->validate(['slack_webhook_url' => 'nullable|url']);
        \App\Models\Setting::set('slack_webhook_url', $request->slack_webhook_url);
        return redirect()->route('admin.pages.index')->with('success', 'Slack webhook saved.');
    }

    public function developerSupport()
    {
        $developer = [
            'name'  => 'Chuquel P. Perez',
            'role'  => 'Web Developer',
            'phone' => '09932574463',
            'email' => 'johnchuquel@gmail.com',
        ];

        return view('admin.developer-support', compact('developer'));
    }
}
