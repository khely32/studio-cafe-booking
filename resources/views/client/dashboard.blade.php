@extends('client.layout')
@section('title', 'My Bookings')

@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title">My Bookings</h1>
        <p style="font-size:13px;color:var(--gray-500);margin-top:2px;">View and manage your studio sessions</p>
    </div>
    <a href="{{ route('booking.index') }}" class="btn btn-gold">Book New Session</a>
</div>

<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-label">Total Bookings</div>
        <div class="stat-value">{{ $bookings->total() }}</div>
    </div>
    <div class="stat-card">
        <div class="stat-label">Confirmed</div>
        <div class="stat-value" style="color:var(--green);">{{ \App\Models\Booking::where('customer_email', $user->email)->where('status', 'confirmed')->count() }}</div>
    </div>
    <div class="stat-card">
        <div class="stat-label">Pending</div>
        <div class="stat-value" style="color:var(--orange);">{{ \App\Models\Booking::where('customer_email', $user->email)->where('status', 'pending')->count() }}</div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h2>Booking History</h2>
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
                    <td><span class="badge badge-{{ $booking->status }}">{{ ucfirst($booking->status) }}</span></td>
                    <td><a href="{{ route('client.booking.detail', $booking) }}" class="btn btn-secondary" style="padding:4px 10px;font-size:11px;">View</a></td>
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
        <a href="{{ route('booking.index') }}" class="btn btn-gold" style="margin-top:16px;">Book Now</a>
    </div>
    @endif
</div>
@endsection
