<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ClientDashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $bookings = Booking::with('service')
            ->where('customer_email', $user->email)
            ->orderBy('booking_date', 'desc')
            ->orderBy('booking_time', 'desc')
            ->paginate(10);

        return view('client.dashboard', compact('bookings', 'user'));
    }

    public function bookingDetail(Booking $booking)
    {
        $user = Auth::user();

        if ($booking->customer_email !== $user->email) {
            abort(403);
        }

        $booking->load('service', 'addons');
        return view('client.booking-detail', compact('booking'));
    }
}
