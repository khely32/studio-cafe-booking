<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
            if ($request->filled('date_from')) {
                $query->where('booking_date', '>=', $request->date_from);
            }
            if ($request->filled('date_to')) {
                $query->where('booking_date', '<=', $request->date_to);
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
}
