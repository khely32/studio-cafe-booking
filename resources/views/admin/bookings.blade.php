@extends('admin.layout')
@section('title', 'Bookings')

@section('styles')
<style>
:root {
    --bk-primary: #000000;
    --bk-text: #111;
    --bk-text-muted: #6B7280;
    --bk-border: #E5E7EB;
    --bk-bg: #F9FAFB;
}

/* ── Page wrapper ── */
.bk-page {
    padding: 28px 32px;
    background: #F4F6F8;
    min-height: calc(100vh - 64px);
}

/* ── Header ── */
.bk-header {
    display: flex; align-items: center; justify-content: space-between;
    margin-bottom: 20px;
}
.bk-header h1 { font-size: 22px; font-weight: 700; color: #111; }
.bk-header-right { display: flex; align-items: center; gap: 12px; }

.bk-controls {
    display: flex; align-items: center;
    border: 1px solid #D1D5DB; border-radius: 100px;
    background: #fff; overflow: hidden;
}
.bk-controls .ctl-btn {
    display: inline-flex; align-items: center; gap: 6px;
    padding: 8px 16px; font-size: 13px; font-weight: 500;
    background: transparent; border: none; color: #6B7280;
    cursor: pointer; font-family: inherit;
    transition: all 0.15s; white-space: nowrap;
}
.bk-controls .ctl-btn:not(:last-child) { border-right: 1px solid #E5E7EB; }
.bk-controls .ctl-btn:hover { color: #111; background: #F9FAFB; }
.bk-controls .ctl-btn.active { color: #000; font-weight: 600; position: relative; }
.bk-controls .ctl-btn.active::after {
    content: ''; position: absolute; bottom: -1px; left: 50%; transform: translateX(-50%);
    width: 16px; height: 2px; border-radius: 1px; background: #000;
}
.bk-controls .ctl-btn svg { width: 15px; height: 15px; }

.bk-export {
    display: inline-flex; align-items: center; gap: 6px;
    padding: 9px 20px; border-radius: 100px;
    background: #000; color: #fff;
    font-size: 13px; font-weight: 600; border: none;
    cursor: pointer; font-family: inherit;
    transition: all 0.2s;
}
.bk-export:hover { background: #1A1A1A; box-shadow: 0 4px 12px rgba(0,0,0,0.2); }
.bk-export svg { width: 15px; height: 15px; }

/* ── Pagination bar ── */
.bk-pagination {
    display: flex; align-items: center; justify-content: flex-end; gap: 12px;
    margin-bottom: 16px;
}
.bk-pagination .pg-text { font-size: 13px; color: #6B7280; }
.bk-pagination .pg-arrows { display: flex; gap: 4px; }
.bk-pagination .pg-arrow {
    width: 32px; height: 32px; border-radius: 8px;
    display: flex; align-items: center; justify-content: center;
    border: 1px solid #D1D5DB; background: #fff;
    color: #6B7280; cursor: pointer; transition: all 0.15s;
}
.bk-pagination .pg-arrow:hover { border-color: #000; color: #000; }
.bk-pagination .pg-arrow.disabled { opacity: 0.4; cursor: not-allowed; }
.bk-pagination .pg-arrow.disabled:hover { border-color: #D1D5DB; color: #6B7280; }

/* ── Table ── */
.bk-table-wrap {
    background: #fff; border: 1px solid #E5E7EB;
    border-radius: 16px; overflow: hidden;
}
.bk-table { width: 100%; border-collapse: collapse; }
.bk-table thead th {
    padding: 12px 16px; text-align: left;
    font-size: 11px; font-weight: 600;
    color: #6B7280; text-transform: uppercase; letter-spacing: 0.5px;
    background: #F9FAFB; border-bottom: 1px solid #E5E7EB;
}
.bk-table tbody td {
    padding: 14px 16px; border-bottom: 1px solid #F3F4F6;
    vertical-align: middle; font-size: 13px; color: #111;
}
.bk-table tbody tr:last-child td { border-bottom: none; }

/* Accent bar column */
.bk-table td:first-child, .bk-table th:first-child {
    width: 4px; padding: 0;
}
.bk-accent { width: 4px; height: 100%; min-height: 58px; }
.bk-accent.accepted { background: #1E7E52; }
.bk-accent.undecided { background: #D97706; }

.bk-table .td-date { font-weight: 600; color: #111; white-space: nowrap; }
.bk-table .td-time { color: #6B7280; font-size: 12px; white-space: nowrap; }
.bk-table .td-duration { color: #6B7280; font-size: 12px; }
.bk-table .td-name { font-weight: 600; color: #111; }
.bk-table .td-handle { font-size: 12px; color: #9CA3AF; }
.bk-table .td-type { font-weight: 500; }

/* Status badges */
.badge-status {
    display: inline-flex; align-items: center;
    padding: 4px 12px; border-radius: 9999px;
    font-size: 11px; font-weight: 600; letter-spacing: 0.2px;
}
.badge-status.accepted { background: #E6F4EA; color: #1E7E52; }
.badge-status.undecided { background: #FEF3C7; color: #B45309; }

/* Action dots */
.bk-action {
    color: #9CA3AF; cursor: pointer; padding: 4px;
    display: inline-flex; align-items: center; justify-content: center;
    border-radius: 6px; transition: all 0.15s;
}
.bk-action:hover { background: #F3F4F6; color: #111; }
.bk-action svg { width: 16px; height: 16px; }

@keyframes fadeUp { from { opacity: 0; transform: translateY(12px); } to { opacity: 1; transform: translateY(0); } }
.anim { animation: fadeUp 0.4s cubic-bezier(0.4,0,0.2,1) both; }
.anim-d1 { animation-delay: 0.05s; }
.anim-d2 { animation-delay: 0.10s; }

@media (max-width: 1100px) {
    .bk-page { padding: 20px 16px; }
    .bk-header { flex-direction: column; align-items: flex-start; gap: 12px; }
    .bk-table-wrap { overflow-x: auto; }
}
</style>
@endsection

@section('content')
<div class="bk-page">

    {{-- Header --}}
    <div class="bk-header anim anim-d2">
        <h1>Upcoming bookings</h1>
        <div class="bk-header-right">
            <div class="bk-controls">
                <button class="ctl-btn">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="4" y1="21" x2="4" y2="14"/><line x1="4" y1="10" x2="4" y2="3"/><line x1="12" y1="21" x2="12" y2="12"/><line x1="12" y1="8" x2="12" y2="3"/><line x1="20" y1="21" x2="20" y2="16"/><line x1="20" y1="12" x2="20" y2="3"/><line x1="1" y1="14" x2="7" y2="14"/><line x1="9" y1="8" x2="15" y2="8"/><line x1="17" y1="16" x2="23" y2="16"/></svg>
                    Filters
                </button>
                <a href="{{ route('admin.bookings', ['filter' => 'upcoming']) }}" class="ctl-btn active">Upcoming</a>
                <a href="{{ route('admin.bookings', ['filter' => 'past']) }}" class="ctl-btn">Past</a>
                <button class="ctl-btn">Date Range</button>
            </div>
            <button class="bk-export">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                Export
            </button>
        </div>
    </div>

    {{-- Pagination bar --}}
    <div class="bk-pagination anim anim-d2">
        <span class="pg-text">1 &mdash; 10 of {{ $bookings->total() }}</span>
        <div class="pg-arrows">
            <span class="pg-arrow disabled">&lsaquo;</span>
            <span class="pg-arrow">&rsaquo;</span>
        </div>
    </div>

    {{-- Table --}}
    <div class="bk-table-wrap anim anim-d2">
        <table class="bk-table">
            <thead>
                <tr>
                    <th style="width:4px;padding:0;"></th>
                    <th>Date</th>
                    <th>Time</th>
                    <th>Duration</th>
                    <th>Booking</th>
                    <th>Team</th>
                    <th>Appointment type</th>
                    <th>Status</th>
                    <th style="width:32px;"></th>
                </tr>
            </thead>
            <tbody>
                @forelse($bookings as $b)
                @php
                    $isAccepted = in_array($b->status, ['confirmed', 'completed']);
                    $statusLabel = $isAccepted ? 'Accepted' : 'Undecided';
                    $statusClass = $isAccepted ? 'accepted' : 'undecided';
                    $duration = $b->service->duration_minutes ?? 30;
                    $durationFmt = $duration >= 60 ? floor($duration/60).' hour'.($duration>=120?'s':'') : $duration.' minutes';
                @endphp
                <tr>
                    <td style="width:4px;padding:0;">
                        <div class="bk-accent {{ $statusClass }}"></div>
                    </td>
                    <td><span class="td-date">{{ \Carbon\Carbon::parse($b->booking_date)->format('D M j, Y') }}</span></td>
                    <td><span class="td-time">{{ \Carbon\Carbon::parse($b->booking_time)->format('g:i A') }}</span></td>
                    <td><span class="td-duration">{{ $durationFmt }}</span></td>
                    <td>
                        <div class="td-name">{{ $b->customer_name }} &amp; 56'30 Studio Cafe</div>
                        <div class="td-handle">5630studiocafe</div>
                    </td>
                    <td style="color:#9CA3AF;font-size:12px;">&mdash;</td>
                    <td><span class="td-type">{{ $b->service->name ?? 'Booking' }}</span></td>
                    <td><span class="badge-status {{ $statusClass }}">{{ $statusLabel }}</span></td>
                    <td>
                        <span class="bk-action">
                            <svg viewBox="0 0 24 24" fill="currentColor"><circle cx="12" cy="5" r="1.5"/><circle cx="12" cy="12" r="1.5"/><circle cx="12" cy="19" r="1.5"/></svg>
                        </span>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="9" style="text-align:center;padding:40px 0;color:#9CA3AF;font-size:13px;">No bookings found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection