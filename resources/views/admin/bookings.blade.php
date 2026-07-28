@extends('admin.layout')
@section('title', 'My Bookings')

@section('content')
<style>
    .bk-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; }
    .bk-header h1 { font-size: 22px; font-weight: 700; color: var(--gray-900); }
    .bk-clear { font-size: 13px; color: var(--cafe); cursor: pointer; background: none; border: none; font-weight: 500; text-decoration: underline; }
    .bk-filters { display: flex; align-items: center; gap: 12px; margin-bottom: 20px; flex-wrap: wrap; }
    .bk-tabs { display: flex; gap: 0; border: 1px solid var(--gray-200); border-radius: var(--radius-sm); overflow: hidden; }
    .bk-tab {
        padding: 8px 20px; font-size: 13px; font-weight: 500; cursor: pointer;
        background: #fff; border: none; color: var(--gray-500); transition: all 0.2s;
        border-right: 1px solid var(--gray-200); font-family: inherit;
    }
    .bk-tab:last-child { border-right: none; }
    .bk-tab.active { background: var(--cafe); color: #fff; }
    .bk-tab:hover:not(.active) { background: var(--gray-50); }
    .bk-date-range { display: flex; align-items: center; gap: 8px; }
    .bk-date-range input {
        padding: 7px 12px; border: 1px solid var(--gray-200); border-radius: var(--radius-sm);
        font-size: 13px; font-family: inherit; background: #fff;
    }
    .bk-date-range input::-webkit-datetime-edit { color: transparent; }
    .bk-date-range input::-webkit-datetime-edit:focus { color: var(--gray-700); }
    .bk-date-range input:focus { outline: none; border-color: var(--cafe); }
    .bk-date-range input::-webkit-calendar-picker-indicator { opacity: 0.4; }
    .bk-date-range span { font-size: 13px; color: var(--gray-400); }
    .bk-count { font-size: 13px; color: var(--gray-500); margin-left: auto; }

    .bk-table-wrap { background: #fff; border: 1px solid var(--gray-200); border-radius: var(--radius); overflow: hidden; }
    .bk-table { width: 100%; border-collapse: collapse; font-size: 13px; }
    .bk-table thead th {
        padding: 12px 16px; text-align: left; font-size: 11px; font-weight: 600;
        color: var(--gray-500); text-transform: uppercase; letter-spacing: 0.5px;
        background: var(--gray-50); border-bottom: 1px solid var(--gray-200);
    }
    .bk-table tbody td {
        padding: 14px 16px; border-bottom: 1px solid var(--gray-100);
        vertical-align: top;
    }
    .bk-table tbody tr:hover { background: var(--gray-50); }
    .bk-table tbody tr:last-child td { border-bottom: none; }

    .bk-date-cell { font-weight: 500; color: var(--gray-800); white-space: nowrap; }
    .bk-time-cell { font-weight: 500; color: var(--gray-700); white-space: nowrap; }
    .bk-duration-cell { color: var(--gray-600); white-space: nowrap; }
    .bk-booking-cell .bk-name { font-weight: 600; color: var(--gray-900); font-size: 14px; }
    .bk-booking-cell .bk-studio { font-size: 12px; color: var(--cafe); margin-top: 1px; }
    .bk-booking-cell .bk-package { font-size: 12px; color: var(--gray-500); margin-top: 3px; }

    .bk-status {
        display: inline-flex; align-items: center; gap: 5px;
        padding: 4px 12px; border-radius: 100px; font-size: 11px; font-weight: 600;
    }
    .bk-status.accepted, .bk-status.confirmed { background: #D1FAE5; color: #065F46; }
    .bk-status.pending { background: #FEF3C7; color: #92400E; }
    .bk-status.cancelled { background: #FEE2E2; color: #991B1B; }
    .bk-status.completed { background: #E0D4C4; color: #5C4A3A; }
    .bk-status-undecided { background: #FEF3C7; color: #92400E; }

    .bk-actions { display: flex; gap: 6px; }
    .bk-actions a, .bk-actions button {
        padding: 5px 12px; border-radius: var(--radius-sm); font-size: 11px; font-weight: 500;
        cursor: pointer; transition: all 0.2s; border: 1px solid var(--gray-200);
        background: #fff; color: var(--gray-600); text-decoration: none; font-family: inherit;
    }
    .bk-actions a:hover, .bk-actions button:hover {
        border-color: var(--cafe); color: var(--cafe); background: rgba(139,111,71,0.04);
        box-shadow: 0 0 8px rgba(139,111,71,0.15);
    }

    .bk-footer {
        display: flex; justify-content: space-between; align-items: center;
        padding: 14px 16px; border-top: 1px solid var(--gray-200);
        font-size: 13px; color: var(--gray-500);
    }
    .bk-pagination { display: flex; gap: 4px; align-items: center; }
    .bk-pagination a, .bk-pagination span {
        padding: 5px 10px; border-radius: var(--radius-sm); font-size: 13px;
        text-decoration: none; color: var(--gray-600); transition: all 0.2s;
    }
    .bk-pagination a:hover { background: var(--gray-100); }
    .bk-pagination .active { background: var(--cafe); color: #fff; }
    .bk-empty { padding: 60px 20px; text-align: center; color: var(--gray-400); }
    .bk-empty .icon { font-size: 40px; margin-bottom: 10px; }
</style>

<div class="bk-header">
    <h1>{{ request('date_from') || request('date_to') ? 'Filtered bookings' : ($filter === 'past' ? 'Past bookings' : 'Upcoming bookings') }}</h1>
    @if(request('date_from') || request('date_to') || request('search'))
        <a href="{{ route('admin.bookings', ['filter' => $filter]) }}" class="bk-clear">Clear all</a>
    @endif
</div>

<div class="bk-filters">
    <span style="font-size:13px;font-weight:600;color:var(--gray-700);">Filters</span>
    <div class="bk-tabs">
        @php $dateParams = array_filter(['date_from' => request('date_from'), 'date_to' => request('date_to')]); @endphp
        <a href="{{ route('admin.bookings', array_merge($dateParams, ['filter'=>'upcoming'])) }}"
           class="bk-tab {{ $filter==='upcoming'?'active':'' }}">Upcoming</a>
        <a href="{{ route('admin.bookings', array_merge($dateParams, ['filter'=>'past'])) }}"
           class="bk-tab {{ $filter==='past'?'active':'' }}">Past</a>
    </div>
    <div class="bk-date-range">
        <span>Date Range</span>
        <form method="GET" style="display:flex;gap:8px;align-items:center;">
            <input type="hidden" name="filter" value="{{ $filter }}">
            <input type="date" name="date_from" value="{{ request('date_from') }}" placeholder="From">
            <span>—</span>
            <input type="date" name="date_to" value="{{ request('date_to') }}" placeholder="To">
            <button type="submit" class="bk-tab active" style="border-radius:var(--radius-sm);border:none;">Apply</button>
        </form>
    </div>
    <div class="bk-count">{{ $bookings->firstItem() }} — {{ $bookings->lastItem() }} of {{ $bookings->total() }}</div>
</div>

<div class="bk-table-wrap">
    @if($bookings->isEmpty())
    <div class="bk-empty">
        <div class="icon">📅</div>
        <p>No {{ $filter }} bookings found.</p>
    </div>
    @else
    <table class="bk-table">
        <thead>
            <tr>
                <th>Date</th>
                <th>Time</th>
                <th>Duration</th>
                <th>Booking</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($bookings as $b)
            <tr>
                <td class="bk-date-cell">{{ $b->booking_date->format('D, M j, Y') }}</td>
                <td class="bk-time-cell">{{ \Carbon\Carbon::parse($b->booking_time)->format('g:i A') }}</td>
                <td class="bk-duration-cell">{{ $b->service->duration_minutes ?? 20 }} minutes</td>
                <td class="bk-booking-cell">
                    <div class="bk-name">{{ $b->customer_name }} and 56'30 Studio Cafe</div>
                    <div class="bk-studio">5630studiocafe</div>
                    <div class="bk-package">{{ $b->service->name ?? 'N/A' }}</div>
                </td>
                <td>
                    @if($b->status === 'confirmed' || $b->status === 'completed')
                        <span class="bk-status confirmed">Accepted</span>
                    @elseif($b->status === 'pending')
                        <span class="bk-status-undecided" style="display:inline-flex;padding:4px 12px;border-radius:100px;font-size:11px;font-weight:600;background:#FEF3C7;color:#92400E;">Undecided</span>
                    @elseif($b->status === 'cancelled')
                        <span class="bk-status cancelled">Cancelled</span>
                    @else
                        <span class="bk-status" style="background:var(--gray-100);color:var(--gray-600);">{{ ucfirst($b->status) }}</span>
                    @endif
                </td>
                <td>
                    <div class="bk-actions">
                        <a href="{{ route('admin.booking.detail', $b) }}">View</a>
                    </div>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @endif

    @if($bookings->hasPages())
    <div class="bk-footer">
        <span>{{ \Carbon\Carbon::now()->format('g:i A') }} Asia/Manila</span>
        <div class="bk-pagination">
            {{ $bookings->links() }}
        </div>
    </div>
    @endif
</div>
@endsection
