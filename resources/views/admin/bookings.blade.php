@extends('admin.layout')
@section('title', 'Bookings')

@section('styles')
<style>
/* ── Page wrapper ── */
.bk-page {
    padding: 28px 32px;
    background: #F8F9FA;
    min-height: calc(100vh - 64px);
}

/* ── Info alert banner ── */
.bk-alert {
    display: flex; align-items: center; gap: 10px;
    background: #EFF6FF; color: #1E40AF;
    border: 1px solid #BFDBFE; border-radius: 12px;
    padding: 12px 18px; margin-bottom: 24px;
    font-size: 13px; font-weight: 500;
}
.bk-alert svg { width: 18px; height: 18px; flex-shrink: 0; }
.bk-alert u { text-underline-offset: 3px; }

/* ── Header ── */
.bk-header {
    display: flex; align-items: center; justify-content: space-between;
    margin-bottom: 20px; gap: 16px; flex-wrap: wrap;
}
.bk-header h1 { font-size: 26px; font-weight: 700; color: #111827; letter-spacing: -0.4px; }
.bk-header-right { display: flex; align-items: center; gap: 12px; flex-wrap: wrap; }

.bk-controls {
    display: flex; align-items: center;
    border: 1px solid #D1D5DB; border-radius: 100px;
    background: #fff; overflow: visible; position: relative;
    box-shadow: 0 1px 2px rgba(0,0,0,0.04);
}
.bk-controls .ctl-btn {
    display: inline-flex; align-items: center; gap: 6px;
    padding: 9px 16px; font-size: 13px; font-weight: 500;
    background: transparent; border: none; color: #6B7280;
    cursor: pointer; font-family: inherit;
    transition: all 0.15s; white-space: nowrap;
}
.bk-controls .ctl-btn:not(:last-child) { border-right: 1px solid #E5E7EB; }
.bk-controls .ctl-btn:hover { color: #111; background: #F9FAFB; border-radius: 100px; }
.bk-controls .ctl-btn.active { color: #111827; font-weight: 600; position: relative; }
.bk-controls .ctl-btn.active::after {
    content: ''; position: absolute; bottom: -1px; left: 50%; transform: translateX(-50%);
    width: 18px; height: 2.5px; border-radius: 2px; background: #1A3B32;
}
.bk-controls .ctl-btn svg { width: 15px; height: 15px; }

/* Filter / date-range popovers */
.bk-pop {
    position: absolute; top: calc(100% + 10px); left: 0;
    background: #fff; border: 1px solid #E5E7EB; border-radius: 14px;
    box-shadow: 0 12px 40px rgba(0,0,0,0.12);
    padding: 16px; min-width: 240px; z-index: 300;
    display: none;
}
.bk-pop.open { display: block; }
.bk-pop label { display: block; font-size: 12px; font-weight: 600; color: #6B7280; margin: 0 0 6px; }
.bk-pop .pop-field { margin-bottom: 12px; }
.bk-pop input, .bk-pop select {
    width: 100%; padding: 8px 12px;
    border: 1px solid #D1D5DB; border-radius: 8px;
    font-size: 13px; font-family: inherit; color: #111827; background: #fff;
}
.bk-pop input:focus, .bk-pop select:focus { outline: none; border-color: #1A3B32; box-shadow: 0 0 0 3px rgba(26,59,50,0.1); }
.bk-pop .pop-actions { display: flex; gap: 8px; justify-content: flex-end; margin-top: 4px; }
.bk-pop-range { left: auto; right: 0; }

.bk-export {
    display: inline-flex; align-items: center; gap: 6px;
    padding: 10px 22px; border-radius: 100px;
    background: #1A3B32; color: #fff;
    font-size: 13px; font-weight: 600; border: none;
    cursor: pointer; font-family: inherit; text-decoration: none;
    transition: all 0.2s;
}
.bk-export:hover { background: #2A554A; box-shadow: 0 6px 16px rgba(26,59,50,0.3); transform: translateY(-1px); }
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
    display: inline-flex; align-items: center; justify-content: center;
    border: 1px solid #D1D5DB; background: #fff;
    color: #6B7280; cursor: pointer; transition: all 0.15s;
    text-decoration: none; font-size: 16px;
}
.bk-pagination .pg-arrow:hover { border-color: #1A3B32; color: #1A3B32; }
.bk-pagination .pg-arrow.disabled { opacity: 0.4; cursor: not-allowed; pointer-events: none; }

/* ── Table ── */
.bk-table-wrap {
    background: #fff; border: 1px solid #E5E7EB;
    border-radius: 16px; overflow: hidden;
    box-shadow: 0 1px 3px rgba(0,0,0,0.04);
}
.bk-table { width: 100%; border-collapse: collapse; }
.bk-table thead th {
    padding: 12px 16px; text-align: left;
    font-size: 11px; font-weight: 600;
    color: #6B7280; text-transform: uppercase; letter-spacing: 0.5px;
    background: #F9FAFB; border-bottom: 1px solid #E5E7EB;
}
.bk-table tbody td {
    padding: 16px; border-bottom: 1px solid #F3F4F6;
    vertical-align: middle; font-size: 13px; color: #111827;
}
.bk-table tbody tr:last-child td { border-bottom: none; }
.bk-table tbody tr { transition: background 0.15s; }
.bk-table tbody tr:hover { background: #FAFAF9; }

/* Accent bar column */
.bk-table td:first-child, .bk-table th:first-child {
    width: 4px; padding: 0;
}
.bk-accent { width: 4px; min-height: 62px; }
.bk-accent.accepted { background: #1A3B32; }
.bk-accent.undecided { background: #D97706; }
.bk-accent.cancelled { background: #EF4444; }
.bk-accent.other { background: #9CA3AF; }

.bk-table .td-date { font-weight: 600; color: #111827; white-space: nowrap; }
.bk-table .td-time { color: #6B7280; font-size: 12px; white-space: nowrap; }
.bk-table .td-duration { color: #6B7280; font-size: 12px; }
.bk-table .td-name { font-weight: 600; color: #111827; }
.bk-table .td-handle { font-size: 12px; color: #9CA3AF; margin-top: 2px; }
.bk-table .td-type { font-weight: 500; color: #374151; }

/* Status badges */
.badge-status {
    display: inline-flex; align-items: center;
    padding: 5px 12px; border-radius: 9999px;
    font-size: 11px; font-weight: 600; letter-spacing: 0.2px;
}
.badge-status.accepted { background: #E7F1EC; color: #1A3B32; }
.badge-status.undecided { background: #FEF3C7; color: #B45309; }
.badge-status.cancelled { background: #FEE2E2; color: #B91C1C; }
.badge-status.other { background: #F3F4F6; color: #6B7280; }

/* 3-dot action menu */
.bk-menu { position: relative; display: inline-flex; }
.bk-action {
    color: #9CA3AF; cursor: pointer; padding: 6px;
    display: inline-flex; align-items: center; justify-content: center;
    border-radius: 8px; transition: all 0.15s; background: none; border: none;
    font-family: inherit;
}
.bk-action:hover { background: #F3F4F6; color: #111827; }
.bk-action svg { width: 16px; height: 16px; }
.bk-menu-drop {
    position: absolute; top: calc(100% + 6px); right: 0;
    background: #fff; border: 1px solid #E5E7EB; border-radius: 12px;
    box-shadow: 0 12px 40px rgba(0,0,0,0.12);
    min-width: 170px; padding: 6px; z-index: 400;
    display: none;
}
.bk-menu-drop.open { display: block; }
.bk-menu-drop a, .bk-menu-drop button {
    display: flex; align-items: center; gap: 8px;
    width: 100%; padding: 9px 12px; border-radius: 8px;
    font-size: 13px; color: #374151; cursor: pointer;
    text-decoration: none; background: none; border: none;
    font-family: inherit; text-align: left; transition: background 0.15s;
}
.bk-menu-drop a:hover, .bk-menu-drop button:hover { background: #F9FAFB; color: #111827; }
.bk-menu-drop button.danger { color: #DC2626; }
.bk-menu-drop button.danger:hover { background: #FEF2F2; }
.bk-menu-drop svg { width: 15px; height: 15px; color: #9CA3AF; }
.bk-menu-drop .menu-divider { height: 1px; background: #F3F4F6; margin: 4px 6px; }

@keyframes fadeUp { from { opacity: 0; transform: translateY(12px); } to { opacity: 1; transform: translateY(0); } }
.anim { animation: fadeUp 0.4s cubic-bezier(0.4,0,0.2,1) both; }
.anim-d1 { animation-delay: 0.05s; }
.anim-d2 { animation-delay: 0.10s; }
.anim-d3 { animation-delay: 0.15s; }

@media (max-width: 1100px) {
    .bk-page { padding: 20px 16px; }
    .bk-header { flex-direction: column; align-items: flex-start; }
    .bk-table-wrap { overflow-x: auto; }
}
</style>
@endsection

@section('content')
<div class="bk-page">

    {{-- Info alert banner --}}
    <div class="bk-alert anim anim-d1">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/></svg>
        <span>Purchase <u>SMS credits</u> to make sure future SMS notifications send.</span>
    </div>

    {{-- Header --}}
    <div class="bk-header anim anim-d2">
        <h1>Upcoming bookings</h1>
        <div class="bk-header-right">
            <div class="bk-controls" id="bkControls">
                <button class="ctl-btn" onclick="togglePop('filterPop', 'ctrlFilter')" id="ctrlFilter">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="4" y1="21" x2="4" y2="14"/><line x1="4" y1="10" x2="4" y2="3"/><line x1="12" y1="21" x2="12" y2="12"/><line x1="12" y1="8" x2="12" y2="3"/><line x1="20" y1="21" x2="20" y2="16"/><line x1="20" y1="12" x2="20" y2="3"/><line x1="1" y1="14" x2="7" y2="14"/><line x1="9" y1="8" x2="15" y2="8"/><line x1="17" y1="16" x2="23" y2="16"/></svg>
                    Filters
                </button>
                <a href="{{ route('admin.bookings', ['filter' => 'upcoming'] + request()->except(['filter','page'])) }}" class="ctl-btn {{ $filter === 'upcoming' ? 'active' : '' }}">Upcoming</a>
                <a href="{{ route('admin.bookings', ['filter' => 'past'] + request()->except(['filter','page'])) }}" class="ctl-btn {{ $filter === 'past' ? 'active' : '' }}">Past</a>
                <button class="ctl-btn" onclick="togglePop('rangePop', 'ctrlRange')" id="ctrlRange">Date Range</button>

                <div class="bk-pop" id="filterPop">
                    <form method="GET" action="{{ route('admin.bookings') }}">
                        <input type="hidden" name="filter" value="{{ $filter }}">
                        <div class="pop-field">
                            <label>Search</label>
                            <input type="text" name="search" value="{{ request('search') }}" placeholder="Name, email, ref...">
                        </div>
                        <div class="pop-field">
                            <label>Status</label>
                            <select name="status">
                                <option value="">All statuses</option>
                                <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="confirmed" {{ request('status') === 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                                <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Completed</option>
                                <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                <option value="no_show" {{ request('status') === 'no_show' ? 'selected' : '' }}>No show</option>
                            </select>
                        </div>
                        <div class="pop-actions">
                            <a href="{{ route('admin.bookings', ['filter' => $filter]) }}" style="font-size:12px;color:#6B7280;text-decoration:none;align-self:center;">Reset</a>
                            <button type="submit" class="btn btn-primary btn-sm">Apply</button>
                        </div>
                    </form>
                </div>

                <div class="bk-pop bk-pop-range" id="rangePop">
                    <form method="GET" action="{{ route('admin.bookings') }}">
                        <div class="pop-field">
                            <label>From</label>
                            <input type="date" name="date_from" value="{{ request('date_from') }}">
                        </div>
                        <div class="pop-field">
                            <label>To</label>
                            <input type="date" name="date_to" value="{{ request('date_to') }}">
                        </div>
                        <div class="pop-actions">
                            <a href="{{ route('admin.bookings', ['filter' => $filter]) }}" style="font-size:12px;color:#6B7280;text-decoration:none;align-self:center;">Reset</a>
                            <button type="submit" class="btn btn-primary btn-sm">Apply</button>
                        </div>
                    </form>
                </div>
            </div>
            <button class="bk-export" onclick="exportBookings()">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                Export
            </button>
        </div>
    </div>

    {{-- Pagination bar --}}
    <div class="bk-pagination anim anim-d2">
        <span class="pg-text">{{ $bookings->firstItem() ?? 0 }} &mdash; {{ $bookings->lastItem() ?? 0 }} of {{ $bookings->total() }}</span>
        <div class="pg-arrows">
            @if($bookings->onFirstPage())
            <span class="pg-arrow disabled">&lsaquo;</span>
            @else
            <a href="{{ $bookings->previousPageUrl() }}" class="pg-arrow">&lsaquo;</a>
            @endif
            @if($bookings->hasMorePages())
            <a href="{{ $bookings->nextPageUrl() }}" class="pg-arrow">&rsaquo;</a>
            @else
            <span class="pg-arrow disabled">&rsaquo;</span>
            @endif
        </div>
    </div>

    {{-- Table --}}
    <div class="bk-table-wrap anim anim-d3">
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
                    <th style="width:44px;"></th>
                </tr>
            </thead>
            <tbody>
                @forelse($bookings as $b)
                @php
                    $isAccepted = in_array($b->status, ['confirmed', 'completed']);
                    $isCancelled = in_array($b->status, ['cancelled', 'no_show']);
                    if ($isAccepted) { $statusLabel = 'Accepted'; $statusClass = 'accepted'; }
                    elseif ($isCancelled) { $statusLabel = $b->status === 'no_show' ? 'No Show' : 'Cancelled'; $statusClass = 'cancelled'; }
                    else { $statusLabel = 'Undecided'; $statusClass = 'undecided'; }
                    $duration = $b->service->duration_minutes ?? 30;
                    $durationFmt = $duration >= 60 ? floor($duration/60).' hour'.($duration>=120?'s':'').($duration%60 ? ' '.($duration%60).' minutes' : '') : $duration.' minutes';
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
                        <div class="bk-menu">
                            <button class="bk-action" onclick="toggleMenu(this)">
                                <svg viewBox="0 0 24 24" fill="currentColor"><circle cx="12" cy="5" r="1.5"/><circle cx="12" cy="12" r="1.5"/><circle cx="12" cy="19" r="1.5"/></svg>
                            </button>
                            <div class="bk-menu-drop">
                                <a href="{{ route('admin.booking.detail', $b) }}">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                                    View details
                                </a>
                                @if(!$isAccepted && !$isCancelled)
                                <form method="POST" action="{{ route('admin.booking.update', $b) }}">
                                    @csrf @method('PATCH')
                                    <input type="hidden" name="status" value="confirmed">
                                    <button type="submit">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                                        Mark as accepted
                                    </button>
                                </form>
                                @endif
                                <div class="menu-divider"></div>
                                <form method="POST" action="{{ route('admin.booking.update', $b) }}" onsubmit="return confirm('Cancel this booking?')">
                                    @csrf @method('PATCH')
                                    <input type="hidden" name="status" value="cancelled">
                                    <button type="submit" class="danger">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                                        Cancel booking
                                    </button>
                                </form>
                            </div>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="9" style="text-align:center;padding:48px 0;color:#9CA3AF;font-size:13px;">No bookings found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<script>
function togglePop(popId) {
    const pop = document.getElementById(popId);
    const wasOpen = pop.classList.contains('open');
    document.querySelectorAll('.bk-pop').forEach(p => p.classList.remove('open'));
    if (!wasOpen) pop.classList.add('open');
}

function toggleMenu(btn) {
    const menu = btn.parentElement.querySelector('.bk-menu-drop');
    const wasOpen = menu.classList.contains('open');
    document.querySelectorAll('.bk-menu-drop').forEach(m => m.classList.remove('open'));
    if (!wasOpen) menu.classList.add('open');
}

document.addEventListener('click', function(e) {
    if (!e.target.closest('.bk-controls')) {
        document.querySelectorAll('.bk-pop').forEach(p => p.classList.remove('open'));
    }
    if (!e.target.closest('.bk-menu')) {
        document.querySelectorAll('.bk-menu-drop').forEach(m => m.classList.remove('open'));
    }
});

function exportBookings() {
    const rows = document.querySelectorAll('.bk-table tbody tr');
    if (!rows.length) return;
    const header = ['Date','Time','Duration','Booking','Team','Appointment type','Status'];
    const lines = [header.join(',')];
    rows.forEach(function(row) {
        const cells = row.querySelectorAll('td');
        const cols = [];
        for (let i = 1; i < cells.length - 1; i++) {
            let txt = cells[i].textContent.trim();
            if (txt.includes(',')) txt = '"' + txt + '"';
            cols.push(txt);
        }
        lines.push(cols.join(','));
    });
    const blob = new Blob(['\uFEFF' + lines.join('\n')], { type: 'text/csv;charset=utf-8' });
    const a = document.createElement('a');
    a.href = URL.createObjectURL(blob);
    a.download = 'bookings.csv';
    a.click();
    URL.revokeObjectURL(a.href);
}
</script>
@endsection
