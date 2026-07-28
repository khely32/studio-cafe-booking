<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Service;
use Illuminate\Http\Request;
use Carbon\Carbon;

class AdminController extends Controller
{
    public function dashboard()
    {
        $today = Carbon::today();
        $startOfWeek = Carbon::now()->startOfWeek();
        $startOfMonth = Carbon::now()->startOfMonth();

        $stats = [
            'today_bookings' => Booking::where('booking_date', $today)->count(),
            'week_bookings' => Booking::whereBetween('booking_date', [$startOfWeek, Carbon::now()->endOfWeek()])->count(),
            'month_bookings' => Booking::where('booking_date', '>=', $startOfMonth)->count(),
            'month_revenue' => Booking::where('booking_date', '>=', $startOfMonth)
                ->whereIn('status', ['confirmed', 'completed'])
                ->sum('amount_paid'),
            'pending_count' => Booking::where('status', 'pending')->count(),
            'confirmed_count' => Booking::where('status', 'confirmed')->count(),
            'cancelled_count' => Booking::where('status', 'cancelled')->count(),
        ];

        $upcomingBookings = Booking::with('service')
            ->where('booking_date', '>=', $today)
            ->whereIn('status', ['pending', 'confirmed'])
            ->orderBy('booking_date')
            ->orderBy('booking_time')
            ->limit(20)
            ->get();

        return view('admin.dashboard', compact('stats', 'upcomingBookings'));
    }

    public function bookings(Request $request)
    {
        $query = Booking::with('service');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('date')) {
            $query->where('booking_date', $request->date);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('customer_name', 'like', "%{$search}%")
                  ->orWhere('customer_email', 'like', "%{$search}%")
                  ->orWhere('booking_ref', 'like', "%{$search}%");
            });
        }

        $bookings = $query->orderBy('booking_date', 'desc')
            ->orderBy('booking_time', 'desc')
            ->paginate(20);

        return view('admin.bookings', compact('bookings'));
    }

    public function updateStatus(Booking $booking, Request $request)
    {
        $request->validate([
            'status' => 'required|in:pending,confirmed,completed,cancelled,no_show',
        ]);

        $booking->update(['status' => $request->status]);

        return redirect()->back()->with('success', 'Booking status updated.');
    }

    public function bookingDetail(Booking $booking)
    {
        $booking->load('service', 'addons');
        return view('admin.detail', compact('booking'));
    }
}
