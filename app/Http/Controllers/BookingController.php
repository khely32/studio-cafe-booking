<?php

namespace App\Http\Controllers;

use App\Models\Service;
use App\Models\Addon;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use App\Mail\BookingConfirmation;
use App\Mail\EmailVerificationCode;

class BookingController extends Controller
{
    public function index()
    {
        $services = Service::active()->get();
        $addons = Addon::active()->get();
        return view('booking.index', compact('services', 'addons'));
    }

    public function showService(Service $service)
    {
        return response()->json([
            'id' => $service->id,
            'name' => $service->name,
            'description' => $service->description,
            'price' => number_format($service->price, 2),
            'duration' => $service->duration_label,
            'max_pax' => $service->max_pax,
            'image' => $service->image,
        ]);
    }

    public function getAvailableSlots(Request $request)
    {
        $request->validate([
            'service_id' => 'required|exists:services,id',
            'date' => 'required|date|after_or_equal:today',
        ]);

        $service = Service::findOrFail($request->service_id);
        $date = $request->date;
        $dayOfWeek = Carbon::parse($date)->dayOfWeek;

        if ($dayOfWeek === 0) {
            return response()->json(['slots' => [], 'message' => 'Studio is closed on Sundays']);
        }

        if ($dayOfWeek === 6) {
            $startHour = 9;
            $endHour = 12;
        } else {
            $startHour = 10;
            $endHour = 17;
        }

        $bookedSlots = Booking::where('booking_date', $date)
            ->whereIn('status', ['pending', 'confirmed'])
            ->pluck('booking_time')
            ->map(fn($time) => Carbon::parse($time)->format('H:i'))
            ->toArray();

        $slots = [];
        $current = Carbon::parse("{$date} {$startHour}:00");
        $closing = Carbon::parse("{$date} {$endHour}:00");

        while ($current->copy()->addMinutes($service->duration_minutes)->lte($closing)) {
            $timeStr = $current->format('H:i');
            $endSlot = $current->copy()->addMinutes($service->duration_minutes);
            $slots[] = [
                'time' => $timeStr,
                'display' => $current->format('g:i A'),
                'end_display' => $endSlot->format('g:i A'),
                'available' => !in_array($timeStr, $bookedSlots),
            ];
            $current->addMinutes(30);
        }

        return response()->json([
            'slots' => $slots,
            'studio_hours' => $dayOfWeek === 6 ? '9:00 AM - 12:00 NN' : '10:00 AM - 5:00 PM',
            'day_label' => Carbon::parse($date)->format('l'),
        ]);
    }

    public function getCalendarDates(Request $request)
    {
        $request->validate([
            'service_id' => 'required|exists:services,id',
            'month' => 'required|date',
        ]);

        $month = Carbon::parse($request->month)->startOfMonth();
        $service = Service::findOrFail($request->service_id);
        $dates = [];

        for ($i = 0; $i < $month->daysInMonth; $i++) {
            $date = $month->copy()->addDays($i);
            if ($date->isPast() || $date->dayOfWeek === 0) {
                $dates[] = [
                    'date' => $date->format('Y-m-d'),
                    'available' => false,
                    'reason' => $date->dayOfWeek === 0 ? 'closed' : 'past',
                ];
                continue;
            }

            if ($date->dayOfWeek === 6) {
                $startHour = 9;
                $endHour = 12;
            } else {
                $startHour = 10;
                $endHour = 17;
            }

            $bookedCount = Booking::where('booking_date', $date->format('Y-m-d'))
                ->whereIn('status', ['pending', 'confirmed'])
                ->count();

            $totalSlots = floor((($endHour - $startHour) * 60) / 30);
            $hasAvailable = $bookedCount < $totalSlots;

            $dates[] = [
                'date' => $date->format('Y-m-d'),
                'day' => $date->format('d'),
                'day_name' => $date->format('D'),
                'available' => $hasAvailable,
                'booked' => $bookedCount,
                'total' => $totalSlots,
            ];
        }

        return response()->json([
            'month' => $month->format('F Y'),
            'dates' => $dates,
        ]);
    }

    public function sendVerificationCode(Request $request)
    {
        $request->validate([
            'email' => 'required|email|max:255',
        ]);

        $email = strtolower($request->email);

        $recent = session()->get('email_verification_sent_at');
        if ($recent && now()->diffInSeconds($recent) < 60) {
            return response()->json([
                'success' => false,
                'message' => 'Please wait a moment before requesting another code.',
            ], 429);
        }

        $code = str_pad((string) random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        try {
            Mail::to($email)->send(new EmailVerificationCode($code));
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error("Verification email failed for {$email}: {$e->getMessage()}");
            return response()->json([
                'success' => false,
                'message' => 'We could not send the code right now. Please try again.',
            ], 502);
        }

        session()->put('email_verification_code', [
            'email' => $email,
            'code_hash' => Hash::make($code),
            'expires_at' => now()->addMinutes(10),
        ]);
        session()->put('email_verification_sent_at', now());

        return response()->json([
            'success' => true,
            'message' => 'Verification code sent to ' . $email . '. Check your inbox (and spam).',
        ]);
    }

    public function verifyEmailCode(Request $request)
    {
        $request->validate([
            'email' => 'required|email|max:255',
            'code' => 'required|string|size:6',
        ]);

        $email = strtolower($request->email);
        $stored = session()->get('email_verification_code');

        if (!$stored || $stored['email'] !== $email) {
            return response()->json([
                'success' => false,
                'message' => 'No code was requested for this email. Please request a code first.',
            ], 422);
        }

        if (now()->greaterThan($stored['expires_at'])) {
            session()->forget('email_verification_code');
            return response()->json([
                'success' => false,
                'message' => 'This code has expired. Please request a new one.',
            ], 422);
        }

        if (!Hash::check($request->code, $stored['code_hash'])) {
            return response()->json([
                'success' => false,
                'message' => 'Incorrect code. Please check and try again.',
            ], 422);
        }

        $this->markEmailVerified($email);

        return response()->json([
            'success' => true,
            'message' => 'Email verified successfully.',
        ]);
    }

    public function resendVerificationCode(Request $request)
    {
        return $this->sendVerificationCode($request);
    }

    protected function markEmailVerified(string $email): void
    {
        session()->put('verified_customer_email', $email);
        session()->forget('email_verification_code');
    }

    protected function isEmailVerified(string $email): bool
    {
        return session()->get('verified_customer_email') === strtolower($email);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'service_id' => 'required|exists:services,id',
            'customer_name' => 'required|string|max:255',
            'customer_email' => 'required|email|max:255',
            'customer_phone' => 'required|string|max:20',
            'num_pax' => 'required|integer|min:1',
            'booking_date' => 'required|date|after_or_equal:today',
            'booking_time' => 'required',
            'special_requests' => 'nullable|string|max:1000',
            'payment_method' => 'required|in:gcash,paymaya,cash,full,downpayment',
            'addon_ids' => 'nullable|array',
            'addon_ids.*' => 'exists:addons,id',
            'agreed_to_policy' => 'required|accepted',
        ]);

        $service = Service::findOrFail($validated['service_id']);
        $totalAmount = $service->price;

        if (!$this->isEmailVerified($validated['customer_email'])) {
            return response()->json([
                'success' => false,
                'message' => 'Please verify your email address before confirming the booking. The verification code was sent to your inbox — enter it to continue.',
                'errors' => ['customer_email' => ['Please verify your email address first.']],
            ], 422);
        }

        if (!empty($validated['addon_ids'])) {
            $addons = Addon::whereIn('id', $validated['addon_ids'])->get();
            $totalAmount += $addons->sum('price');
        }

        $amountPaid = $validated['payment_method'] === 'downpayment'
            ? $totalAmount * 0.5
            : $totalAmount;

        $booking = Booking::create([
            'service_id' => $validated['service_id'],
            'customer_name' => $validated['customer_name'],
            'customer_email' => $validated['customer_email'],
            'customer_phone' => $validated['customer_phone'],
            'num_pax' => $validated['num_pax'],
            'booking_date' => $validated['booking_date'],
            'booking_time' => $validated['booking_time'],
            'special_requests' => $validated['special_requests'] ?? null,
            'total_amount' => $totalAmount,
            'amount_paid' => $amountPaid,
            'payment_method' => $validated['payment_method'],
            'payment_status' => $amountPaid >= $totalAmount ? 'paid' : 'partial',
            'status' => 'confirmed',
            'agreed_to_policy' => true,
        ]);

        if (!empty($validated['addon_ids'])) {
            foreach ($validated['addon_ids'] as $addonId) {
                $addon = Addon::find($addonId);
                $booking->addons()->attach($addonId, [
                    'quantity' => 1,
                    'price_at_time' => $addon->price,
                ]);
            }
        }

        try {
            Mail::to($booking->customer_email)->send(new BookingConfirmation($booking));
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error("Booking confirmation email failed for {$booking->booking_ref}: {$e->getMessage()}");
        }

        return response()->json([
            'success' => true,
            'booking_ref' => $booking->booking_ref,
            'redirect' => route('booking.confirmation', $booking->booking_ref),
        ]);
    }

    public function confirmation(string $bookingRef)
    {
        $booking = Booking::with('service', 'addons')
            ->where('booking_ref', $bookingRef)
            ->firstOrFail();

        return view('booking.confirmation', compact('booking'));
    }
}
