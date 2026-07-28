@extends('admin.layout')
@section('title', 'Analytics')

@section('styles')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.7/dist/chart.umd.min.js"></script>
@endsection

@section('content')
<div class="page-header">
    <div>
        <div class="page-title">Analytics</div>
        <div class="page-subtitle">Booking insights and performance metrics</div>
    </div>
</div>

<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-label">Total Bookings</div>
        <div class="stat-value">{{ $overview['total_bookings'] }}</div>
    </div>
    <div class="stat-card">
        <div class="stat-label">This Month</div>
        <div class="stat-value">{{ $overview['month_bookings'] }}</div>
    </div>
    <div class="stat-card">
        <div class="stat-label">Total Revenue</div>
        <div class="stat-value">₱{{ number_format($overview['total_revenue'], 0) }}</div>
    </div>
    <div class="stat-card">
        <div class="stat-label">Avg. Value</div>
        <div class="stat-value">₱{{ number_format($overview['avg_booking_value'], 0) }}</div>
    </div>
</div>

<div style="display:grid;grid-template-columns:1fr 1fr;gap:24px;margin-bottom:24px;">
    <div class="card">
        <div class="card-header"><h2>Monthly Bookings</h2></div>
        <div class="card-body"><canvas id="bookingsChart" height="200"></canvas></div>
    </div>
    <div class="card">
        <div class="card-header"><h2>Monthly Revenue (₱)</h2></div>
        <div class="card-body"><canvas id="revenueChart" height="200"></canvas></div>
    </div>
</div>

<div style="display:grid;grid-template-columns:1fr 1fr;gap:24px;">
    <div class="card">
        <div class="card-header"><h2>Popular Packages</h2></div>
        <div class="card-body">
            @if($packageBreakdown->isEmpty())
            <p style="color:var(--gray-400);font-size:14px;text-align:center;padding:40px 0;">No data yet.</p>
            @else
            <canvas id="packagesChart" height="200"></canvas>
            @endif
        </div>
    </div>
    <div class="card">
        <div class="card-header"><h2>Peak Time Slots</h2></div>
        <div class="card-body">
            @if($popularTimes->isEmpty())
            <p style="color:var(--gray-400);font-size:14px;text-align:center;padding:40px 0;">No data yet.</p>
            @else
            <canvas id="timesChart" height="200"></canvas>
            @endif
        </div>
    </div>
</div>
@endsection

@section('styles')
@parent
<script>
document.addEventListener('DOMContentLoaded', function() {
    const primary = '#8B6F47', primaryLight = '#C9A96E', green = '#10B981', greenLight = '#34D399';

    const monthlyData = @json($monthlyBookings);
    const months = monthlyData.map(m => m.month);
    const monthCounts = monthlyData.map(m => m.count);
    const monthRevenue = @json($monthlyRevenue).map(m => m.revenue);

    new Chart(document.getElementById('bookingsChart'), {
        type: 'bar',
        data: { labels: months, datasets: [{ label: 'Bookings', data: monthCounts, backgroundColor: primary+'cc', borderRadius: 6, borderSkipped: false }] },
        options: { responsive: true, plugins: { legend: { display: false } }, scales: { y: { beginAtZero: true, ticks: { stepSize: 1 } }, x: { ticks: { maxRotation: 45 } } } }
    });

    new Chart(document.getElementById('revenueChart'), {
        type: 'line',
        data: { labels: months, datasets: [{ label: 'Revenue', data: monthRevenue, borderColor: green, backgroundColor: green+'22', fill: true, tension: 0.4 }] },
        options: { responsive: true, plugins: { legend: { display: false } }, scales: { y: { beginAtZero: true }, x: { ticks: { maxRotation: 45 } } } }
    });

    @if($packageBreakdown->isNotEmpty())
    new Chart(document.getElementById('packagesChart'), {
        type: 'doughnut',
        data: { labels: @json($packageBreakdown->pluck('name')), datasets: [{ data: @json($packageBreakdown->pluck('count')), backgroundColor: [primary, primaryLight, green, greenLight, '#D4A574', '#EF4444', '#6B4F35', '#E8C9A0', '#5C4A3A', '#F0E4D4'] }] },
        options: { responsive: true, plugins: { legend: { position: 'right', labels: { boxWidth: 12 } } } }
    });
    @endif

    @if($popularTimes->isNotEmpty())
    new Chart(document.getElementById('timesChart'), {
        type: 'bar',
        data: { labels: @json($popularTimes->pluck('time')), datasets: [{ label: 'Bookings', data: @json($popularTimes->pluck('count')), backgroundColor: primary+'cc', borderRadius: 6, borderSkipped: false }] },
        options: { responsive: true, plugins: { legend: { display: false } }, scales: { y: { beginAtZero: true, ticks: { stepSize: 1 } } } }
    });
    @endif
});
</script>
@endsection
