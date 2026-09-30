<?php

namespace App\Http\Controllers;

use App\Models\Service;
use App\Models\Addon;
use App\Models\Booking;
use App\Support\StudioSchedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;
use App\Mail\BookingConfirmation;

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
            'price' => (float) $service->price,
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

        if (StudioSchedule::isClosed($date)) {
            return response()->json(['slots' => [], 'message' => 'Studio is closed on Sundays']);
        }

        return response()->json([
            'slots' => StudioSchedule::slotsFor($service, $date),
            'studio_hours' => StudioSchedule::hoursLabel($date),
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

            $bookedCount = Booking::whereDate('booking_date', $date->format('Y-m-d'))
                ->whereIn('status', ['pending', 'confirmed'])
                ->count();

            $hasAvailable = $bookedCount < StudioSchedule::totalSlotCount($date->format('Y-m-d'));

            $dates[] = [
                'date' => $date->format('Y-m-d'),
                'day' => $date->format('d'),
                'day_name' => $date->format('D'),
                'available' => $hasAvailable,
                'booked' => $bookedCount,
                'total' => StudioSchedule::totalSlotCount($date->format('Y-m-d')),
            ];
        }

        return response()->json([
            'month' => $month->format('F Y'),
            'dates' => $dates,
        ]);
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
