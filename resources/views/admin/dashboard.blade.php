@extends('admin.layout')
@section('title', 'Dashboard')

@section('content')
<div class="page-header">
    <div>
        <div class="page-title">Dashboard</div>
        <div class="page-subtitle">Overview of your studio bookings</div>
    </div>
    <div style="display:flex;gap:8px;">
        <a href="{{ route('admin.bookings') }}" class="btn btn-secondary">View All Bookings</a>
    </div>
</div>

<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-label">Today's Bookings</div>
        <div class="stat-value">{{ $stats['today_bookings'] }}</div>
    </div>
    <div class="stat-card">
        <div class="stat-label">This Week</div>
        <div class="stat-value">{{ $stats['week_bookings'] }}</div>
    </div>
    <div class="stat-card">
        <div class="stat-label">This Month</div>
        <div class="stat-value">{{ $stats['month_bookings'] }}</div>
    </div>
    <div class="stat-card">
        <div class="stat-label">Monthly Revenue</div>
        <div class="stat-value">₱{{ number_format($stats['month_revenue'], 0) }}</div>
    </div>
</div>

<div style="display:grid;grid-template-columns:repeat(3,1fr);gap:16px;margin-bottom:32px;">
    <div class="stat-card" style="border-left:3px solid var(--orange);">
        <div class="stat-label">Pending</div>
        <div class="stat-value" style="color:var(--orange);">{{ $stats['pending_count'] }}</div>
    </div>
    <div class="stat-card" style="border-left:3px solid var(--green);">
        <div class="stat-label">Confirmed</div>
        <div class="stat-value" style="color:var(--green);">{{ $stats['confirmed_count'] }}</div>
    </div>
    <div class="stat-card" style="border-left:3px solid var(--red);">
        <div class="stat-label">Cancelled</div>
        <div class="stat-value" style="color:var(--red);">{{ $stats['cancelled_count'] }}</div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h2>Upcoming Bookings</h2>
        <a href="{{ route('admin.bookings') }}" class="btn btn-sm btn-secondary">View All →</a>
    </div>
    @if($upcomingBookings->isEmpty())
    <div class="empty-state"><div class="icon">📅</div><p>No upcoming bookings.</p></div>
    @else
    <table class="table">
        <thead>
            <tr>
                <th>Ref</th>
                <th>Customer</th>
                <th>Package</th>
                <th>Date & Time</th>
                <th>Status</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            @foreach($upcomingBookings as $b)
            <tr>
                <td style="font-family:monospace;font-size:12px;color:var(--primary);">{{ $b->booking_ref }}</td>
                <td>
                    <div style="font-weight:500;">{{ $b->customer_name }}</div>
                    <div style="font-size:12px;color:var(--gray-500);">{{ $b->customer_phone }}</div>
                </td>
                <td>{{ $b->service->name }}</td>
                <td>
                    <div>{{ $b->booking_date->format('M d, Y') }}</div>
                    <div style="font-size:12px;color:var(--gray-500);">{{ \Carbon\Carbon::parse($b->booking_time)->format('g:i A') }}</div>
                </td>
                <td><span class="page-card-badge badge-{{ $b->status_badge }}">{{ ucfirst($b->status) }}</span></td>
                <td><a href="{{ route('admin.booking.detail', $b) }}" class="btn btn-sm btn-ghost">View →</a></td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @endif
</div>
@endsection
