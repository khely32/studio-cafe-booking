@extends('client.layout')
@section('title', 'Booking Details')

@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title">Booking Details</h1>
        <p style="font-size:13px;color:var(--gray-500);margin-top:2px;">Reference: <strong style="font-family:monospace;">{{ $booking->booking_ref }}</strong></p>
    </div>
    <a href="{{ route('client.dashboard') }}" class="btn btn-secondary">Back to Bookings</a>
</div>

<div style="background:rgba(255,255,255,0.8);backdrop-filter:blur(16px);border:1px solid rgba(224,212,196,0.35);border-radius:18px;box-shadow:0 10px 35px rgba(0,0,0,.06);margin-bottom:20px;overflow:hidden;">
    <div style="padding:20px 24px;border-bottom:1px solid rgba(240,232,221,0.6);display:flex;justify-content:space-between;align-items:center;">
        <h2 style="font-family:'Poppins',sans-serif;font-size:15px;font-weight:600;color:var(--gray-900);">Session Info</h2>
        <span class="badge badge-{{ $booking->status === 'confirmed' ? 'success' : ($booking->status === 'pending' ? 'warning' : ($booking->status === 'cancelled' ? 'danger' : 'neutral')) }}">{{ ucfirst($booking->status) }}</span>
    </div>
    <div style="padding:24px;">
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;">
            <div>
                <div style="font-size:12px;color:var(--gray-500);margin-bottom:4px;">Service</div>
                <div style="font-weight:600;font-size:15px;">{{ $booking->service->name ?? 'N/A' }}</div>
            </div>
            <div>
                <div style="font-size:12px;color:var(--gray-500);margin-bottom:4px;">Package Price</div>
                <div style="font-weight:600;font-size:15px;">₱{{ number_format($booking->service->price ?? 0, 2) }}</div>
            </div>
            <div>
                <div style="font-size:12px;color:var(--gray-500);margin-bottom:4px;">Date</div>
                <div style="font-weight:600;font-size:15px;">{{ $booking->booking_date->format('l, F j, Y') }}</div>
            </div>
            <div>
                <div style="font-size:12px;color:var(--gray-500);margin-bottom:4px;">Time</div>
                <div style="font-weight:600;font-size:15px;">{{ $booking->booking_time }}</div>
            </div>
            <div>
                <div style="font-size:12px;color:var(--gray-500);margin-bottom:4px;">Number of Pax</div>
                <div style="font-weight:600;font-size:15px;">{{ $booking->num_pax }}</div>
            </div>
            <div>
                <div style="font-size:12px;color:var(--gray-500);margin-bottom:4px;">Special Requests</div>
                <div style="font-weight:500;font-size:14px;">{{ $booking->special_requests ?: 'None' }}</div>
            </div>
        </div>
    </div>
</div>

@if($booking->addons->count())
<div style="background:rgba(255,255,255,0.8);backdrop-filter:blur(16px);border:1px solid rgba(224,212,196,0.35);border-radius:18px;box-shadow:0 10px 35px rgba(0,0,0,.06);margin-bottom:20px;overflow:hidden;">
    <div style="padding:20px 24px;border-bottom:1px solid rgba(240,232,221,0.6);">
        <h2 style="font-family:'Poppins',sans-serif;font-size:15px;font-weight:600;color:var(--gray-900);">Add-ons</h2>
    </div>
    <div style="padding:24px;">
        @foreach($booking->addons as $addon)
        <div style="display:flex;justify-content:space-between;padding:8px 0;{{ !$loop->last ? 'border-bottom:1px solid rgba(240,232,221,0.6);' : '' }}">
            <span>{{ $addon->name }} × {{ $addon->pivot->quantity }}</span>
            <span style="font-weight:600;">₱{{ number_format($addon->pivot->price_at_time * $addon->pivot->quantity, 2) }}</span>
        </div>
        @endforeach
    </div>
</div>
@endif

<div style="background:rgba(255,255,255,0.8);backdrop-filter:blur(16px);border:1px solid rgba(224,212,196,0.35);border-radius:18px;box-shadow:0 10px 35px rgba(0,0,0,.06);overflow:hidden;">
    <div style="padding:20px 24px;border-bottom:1px solid rgba(240,232,221,0.6);">
        <h2 style="font-family:'Poppins',sans-serif;font-size:15px;font-weight:600;color:var(--gray-900);">Payment</h2>
    </div>
    <div style="padding:24px;">
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;">
            <div>
                <div style="font-size:12px;color:var(--gray-500);margin-bottom:4px;">Total Amount</div>
                <div style="font-weight:700;font-size:20px;">₱{{ number_format($booking->total_amount, 2) }}</div>
            </div>
            <div>
                <div style="font-size:12px;color:var(--gray-500);margin-bottom:4px;">Amount Paid</div>
                <div style="font-weight:700;font-size:20px;color:var(--accent);">₱{{ number_format($booking->amount_paid, 2) }}</div>
            </div>
            <div>
                <div style="font-size:12px;color:var(--gray-500);margin-bottom:4px;">Payment Method</div>
                <div style="font-weight:500;">{{ $booking->payment_method ? ucfirst(str_replace('_', ' ', $booking->payment_method)) : 'N/A' }}</div>
            </div>
            <div>
                <div style="font-size:12px;color:var(--gray-500);margin-bottom:4px;">Payment Status</div>
                <div style="font-weight:500;">{{ ucfirst(str_replace('_', ' ', $booking->payment_status ?? 'unpaid')) }}</div>
            </div>
        </div>
    </div>
</div>
@endsection