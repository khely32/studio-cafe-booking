@extends('admin.layout')
@section('title', 'Booking #{{ $booking->booking_ref }}')

@section('content')
<style>
    .glass-card {
        background: var(--white); border: 1px solid var(--gray-200);
        border-radius: 18px; overflow: hidden;
        box-shadow: 0 10px 35px rgba(0,0,0,.08);
    }
    .glass-card .gc-header {
        padding: 20px 24px;
        border-bottom: 1px solid var(--gray-100);
        display: flex; justify-content: space-between; align-items: center;
    }
    .glass-card .gc-header h2 { font-size: 15px; font-weight: 600; color: var(--gray-900); }
    .glass-card .gc-body { padding: 24px; }
</style>

<div class="page-header">
    <div>
        <div class="page-title">Booking Details</div>
        <div class="page-subtitle" style="font-family:monospace;">{{ $booking->booking_ref }}</div>
    </div>
    <a href="{{ route('admin.bookings') }}" class="btn btn-secondary btn-sm">← Back to Bookings</a>
</div>

<div style="display:grid;grid-template-columns:2fr 1fr;gap:24px;">
    <div>
        <div class="glass-card" style="margin-bottom:24px;">
            <div class="gc-header"><h2>Booking Information</h2></div>
            <div class="gc-body">
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;">
                    <div>
                        <div style="font-size:12px;color:var(--gray-500);margin-bottom:2px;">Package</div>
                        <div style="font-weight:500;">{{ $booking->service->name }}</div>
                    </div>
                    <div>
                        <div style="font-size:12px;color:var(--gray-500);margin-bottom:2px;">Date</div>
                        <div style="font-weight:500;">{{ $booking->booking_date->format('l, F j, Y') }}</div>
                    </div>
                    <div>
                        <div style="font-size:12px;color:var(--gray-500);margin-bottom:2px;">Time</div>
                        <div style="font-weight:500;">{{ \Carbon\Carbon::parse($booking->booking_time)->format('g:i A') }}</div>
                    </div>
                    <div>
                        <div style="font-size:12px;color:var(--gray-500);margin-bottom:2px;">Pax</div>
                        <div style="font-weight:500;">{{ $booking->num_pax }}</div>
                    </div>
                </div>
                @if($booking->special_requests)
                <div style="margin-top:16px;padding:16px;background:rgba(250,248,245,0.8);border-radius:12px;border:1px solid var(--gray-200);">
                    <div style="font-size:12px;font-weight:600;color:var(--gray-500);margin-bottom:4px;">Special Requests</div>
                    <div style="font-size:14px;">{{ $booking->special_requests }}</div>
                </div>
                @endif
            </div>
        </div>

        <div class="glass-card">
            <div class="gc-header"><h2>Customer Information</h2></div>
            <div class="gc-body">
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;">
                    <div>
                        <div style="font-size:12px;color:var(--gray-500);margin-bottom:2px;">Name</div>
                        <div style="font-weight:500;">{{ $booking->customer_name }}</div>
                    </div>
                    <div>
                        <div style="font-size:12px;color:var(--gray-500);margin-bottom:2px;">Email</div>
                        <div style="font-weight:500;">{{ $booking->customer_email }}</div>
                    </div>
                    <div>
                        <div style="font-size:12px;color:var(--gray-500);margin-bottom:2px;">Phone</div>
                        <div style="font-weight:500;">{{ $booking->customer_phone }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div>
        <div class="glass-card" style="margin-bottom:24px;">
            <div class="gc-header"><h2>Status</h2></div>
            <div class="gc-body" style="text-align:center;">
                <span class="badge badge-{{ $booking->status === 'confirmed' ? 'success' : ($booking->status === 'pending' ? 'warning' : ($booking->status === 'cancelled' ? 'danger' : 'neutral')) }}" style="font-size:13px;padding:6px 20px;">{{ ucfirst($booking->status) }}</span>
                <form method="POST" action="{{ route('admin.booking.update', $booking) }}" style="margin-top:16px;">
                    @csrf @method('PATCH')
                    <select name="status" class="form-control" onchange="this.form.submit()">
                        @foreach(['pending','confirmed','completed','cancelled','no_show'] as $s)
                        <option value="{{ $s }}" {{ $booking->status===$s?'selected':'' }}>{{ ucfirst(str_replace('_',' ',$s)) }}</option>
                        @endforeach
                    </select>
                </form>
            </div>
        </div>

        <div class="glass-card">
            <div class="gc-header"><h2>Payment</h2></div>
            <div class="gc-body">
                <div style="display:flex;justify-content:space-between;margin-bottom:12px;">
                    <span style="font-size:13px;color:var(--gray-500);">Total</span>
                    <span style="font-weight:700;font-size:18px;font-family:'Poppins',sans-serif;">₱{{ number_format($booking->total_amount, 2) }}</span>
                </div>
                <div style="display:flex;justify-content:space-between;margin-bottom:12px;">
                    <span style="font-size:13px;color:var(--gray-500);">Paid</span>
                    <span style="font-weight:500;">₱{{ number_format($booking->amount_paid, 2) }}</span>
                </div>
                <div style="display:flex;justify-content:space-between;margin-bottom:12px;">
                    <span style="font-size:13px;color:var(--gray-500);">Method</span>
                    <span style="font-weight:500;text-transform:uppercase;font-size:13px;">{{ $booking->payment_method ?? 'N/A' }}</span>
                </div>
                <div style="display:flex;justify-content:space-between;">
                    <span style="font-size:13px;color:var(--gray-500);">Status</span>
                    <span class="badge {{ $booking->payment_status==='paid' ? 'badge-success' : 'badge-warning' }}">{{ ucfirst($booking->payment_status) }}</span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection