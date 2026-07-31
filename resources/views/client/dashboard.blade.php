@extends('client.layout')
@section('title', 'My Bookings')

@section('content')
<div style="background:linear-gradient(135deg,#3E2723,#6F4E37,#8B6F47);border-radius:20px;padding:36px 40px;margin-bottom:32px;position:relative;overflow:hidden;">
    <div style="position:absolute;inset:0;background:radial-gradient(ellipse at 20% 50%,rgba(201,169,110,0.15),transparent 60%),radial-gradient(ellipse at 80% 50%,rgba(255,255,255,0.05),transparent 50%);pointer-events:none;"></div>
    <div style="position:relative;z-index:1;">
        <div style="display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:16px;">
            <div>
                <h2 style="font-family:'Poppins',sans-serif;font-size:22px;font-weight:700;color:#fff;margin-bottom:4px;">Welcome back, {{ $user->name }}!</h2>
                <p style="color:rgba(255,255,255,0.7);font-size:14px;">Manage your studio sessions and bookings</p>
            </div>
            <a href="{{ route('booking.index') }}" style="display:inline-flex;align-items:center;gap:8px;padding:12px 28px;border-radius:9999px;background:#fff;color:#3E2723;font-weight:600;font-size:14px;text-decoration:none;transition:all 0.2s;box-shadow:0 4px 16px rgba(0,0,0,0.15);">Book New Session</a>
        </div>
    </div>
</div>

<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-label">Total Bookings</div>
        <div class="stat-value">{{ $bookings->total() }}</div>
    </div>
    <div class="stat-card">
        <div class="stat-label">Confirmed</div>
        <div class="stat-value" style="color:var(--accent);">{{ \App\Models\Booking::where('customer_email', $user->email)->where('status', 'confirmed')->count() }}</div>
    </div>
    <div class="stat-card">
        <div class="stat-label">Pending</div>
        <div class="stat-value" style="color:var(--warning);">{{ \App\Models\Booking::where('customer_email', $user->email)->where('status', 'pending')->count() }}</div>
    </div>
</div>

<div style="background:rgba(255,255,255,0.75);backdrop-filter:blur(20px);border:1px solid rgba(224,212,196,0.35);border-radius:18px;box-shadow:0 10px 35px rgba(0,0,0,.06);overflow:hidden;">
    <div style="padding:20px 24px;border-bottom:1px solid rgba(240,232,221,0.6);">
        <h2 style="font-family:'Poppins',sans-serif;font-size:15px;font-weight:600;color:var(--gray-900);">Booking History</h2>
    </div>
    @if($bookings->count())
    <div style="overflow-x:auto;">
        <table class="table">
            <thead>
                <tr>
                    <th>Ref</th>
                    <th>Service</th>
                    <th>Date</th>
                    <th>Time</th>
                    <th>Pax</th>
                    <th>Total</th>
                    <th>Status</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @foreach($bookings as $booking)
                <tr>
                    <td style="font-weight:600;font-family:monospace;">{{ $booking->booking_ref }}</td>
                    <td>{{ $booking->service->name ?? 'N/A' }}</td>
                    <td>{{ $booking->booking_date->format('M d, Y') }}</td>
                    <td>{{ $booking->booking_time }}</td>
                    <td>{{ $booking->num_pax }}</td>
                    <td>₱{{ number_format($booking->total_amount, 2) }}</td>
                    <td><span class="badge badge-{{ $booking->status === 'confirmed' ? 'success' : ($booking->status === 'pending' ? 'warning' : ($booking->status === 'cancelled' ? 'danger' : 'neutral')) }}">{{ ucfirst($booking->status) }}</span></td>
                    <td><a href="{{ route('client.booking.detail', $booking) }}" class="btn btn-ghost btn-sm">View</a></td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div style="padding:16px 22px;">
        {{ $bookings->links() }}
    </div>
    @else
    <div class="empty-state">
        <div class="icon">📸</div>
        <p>No bookings yet. Book your first studio session!</p>
        <a href="{{ route('booking.index') }}" class="btn btn-primary btn-sm" style="margin-top:16px;">Book Now</a>
    </div>
    @endif
</div>
@endsection