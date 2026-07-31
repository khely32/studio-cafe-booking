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
    width: 32px; height: 32px; border-radius: 50%;
    background: #E5E7EB; color: #374151; cursor: pointer; padding: 0;
    display: inline-flex; align-items: center; justify-content: center;
    transition: all 0.15s; border: none; font-family: inherit;
}
.bk-action:hover { background: #D1D5DB; color: #111827; }
.bk-action svg { width: 16px; height: 16px; }
.bk-menu-drop {
    position: absolute; top: calc(100% + 8px); right: 0;
    background: #fff; border: 1px solid #E5E7EB; border-radius: 14px;
    box-shadow: 0 12px 40px rgba(0,0,0,0.12);
    min-width: 200px; padding: 6px; z-index: 400;
    display: none;
}
.bk-menu-drop.open { display: block; }
.bk-menu-drop a, .bk-menu-drop button {
    display: flex; align-items: center; gap: 10px;
    width: 100%; padding: 9px 12px; border-radius: 10px;
    font-size: 13px; font-weight: 500; color: #374151; cursor: pointer;
    text-decoration: none; background: none; border: none;
    font-family: inherit; text-align: left; transition: background 0.15s;
}
.bk-menu-drop a:hover, .bk-menu-drop button:hover { background: #F9FAFB; color: #111827; }
.bk-menu-drop .menu-item-details:focus-visible {
    outline: 2px solid #10B981; outline-offset: -2px; border-radius: 10px;
}
.bk-menu-drop .menu-item-details:focus { outline: 2px solid #10B981; outline-offset: -2px; }
.bk-menu-drop button.danger { color: #DC2626; }
.bk-menu-drop button.danger:hover { background: #FEF2F2; }
.bk-menu-drop .m-icon {
    width: 18px; height: 18px; color: #6B7280; flex-shrink: 0;
    display: inline-flex; align-items: center; justify-content: center;
}
.bk-menu-drop .m-icon svg { width: 16px; height: 16px; }
.bk-menu-drop .crown-badge {
    width: 14px; height: 14px; margin-left: auto; color: #9CA3AF;
    display: inline-flex; align-items: center; justify-content: center;
    background: #F3F4F6; border-radius: 50%;
}
.bk-menu-drop .crown-badge svg { width: 9px; height: 9px; }

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

/* ── Booking Details Modal ── */
.bm-overlay {
    position: fixed; inset: 0; z-index: 9000;
    display: flex; align-items: center; justify-content: center;
    padding: 24px;
}
.bm-backdrop {
    position: absolute; inset: 0;
    background: rgba(17, 24, 39, 0.55);
    backdrop-filter: blur(3px);
    -webkit-backdrop-filter: blur(3px);
}
.bm-modal {
    position: relative; z-index: 1;
    width: 100%; max-width: 1024px; height: 86vh;
    background: #fff; border-radius: 20px;
    box-shadow: 0 32px 80px rgba(0,0,0,0.35);
    display: flex; overflow: hidden;
    animation: bmIn 0.3s cubic-bezier(0.4,0,0.2,1) both;
}
@keyframes bmIn { from { opacity: 0; transform: translateY(18px) scale(0.98); } to { opacity: 1; transform: translateY(0) scale(1); } }

.bm-main {
    width: 70%; overflow-y: auto; padding: 28px 32px;
    background: #fff;
}
.bm-side {
    width: 30%; flex-shrink: 0;
    background: #F8F9FA; border-left: 1px solid #EEF0F2;
    display: flex; flex-direction: column;
}
.bm-side-scroll { flex: 1; overflow-y: auto; padding: 20px; }

.bm-label { font-size: 12px; color: #6B7280; font-weight: 600; text-transform: uppercase; letter-spacing: 0.4px; }
.bm-title { font-size: 24px; font-weight: 700; color: #111827; letter-spacing: -0.4px; margin-top: 4px; }

.bm-hero {
    background: #F9FAFB; border: 1px solid #F3F4F6; border-radius: 14px;
    padding: 18px; margin: 20px 0 28px;
}
.bm-hero-row { display: flex; align-items: center; gap: 8px; margin-top: 10px; font-size: 13px; color: #374151; }
.bm-hero-row:first-child { margin-top: 0; }
.bm-hero-row svg { width: 15px; height: 15px; color: #9CA3AF; flex-shrink: 0; }
.bm-hero .bm-link { color: #2563EB; text-decoration: none; }
.bm-hero .bm-link:hover { text-decoration: underline; }
.bm-hero .bm-badge { margin-right: 6px; }

.bm-section-title { font-size: 15px; font-weight: 700; color: #111827; margin-bottom: 12px; display: flex; align-items: center; gap: 6px; }
.bm-tooltip {
    display: inline-flex; align-items: center; justify-content: center;
    width: 15px; height: 15px; border-radius: 50%;
    background: #E5E7EB; color: #6B7280; font-size: 10px; font-weight: 700; cursor: help;
}
.bm-crown { color: #9CA3AF; display: inline-flex; }
.bm-crown svg { width: 13px; height: 13px; }

.bm-data-card { border: 1px solid #E5E7EB; border-radius: 12px; overflow: hidden; margin-bottom: 28px; }
.bm-row { display: flex; align-items: center; justify-content: space-between; padding: 13px 18px; border-bottom: 1px solid #F3F4F6; }
.bm-row:last-child { border-bottom: none; }
.bm-row .bm-k { font-size: 13px; color: #6B7280; }
.bm-row .bm-v { font-size: 13px; color: #111827; font-weight: 500; display: flex; align-items: center; gap: 6px; }
.bm-edit-link { color: #2563EB; text-decoration: none; font-size: 12px; font-weight: 600; }
.bm-edit-link:hover { text-decoration: underline; }
.bm-show-more {
    display: block; width: 100%; text-align: center; padding: 12px;
    border: none; background: #F9FAFB; color: #6B7280; font-size: 13px; font-weight: 600;
    cursor: pointer; font-family: inherit; border-top: 1px solid #F3F4F6;
    transition: background 0.15s;
}
.bm-show-more:hover { background: #F3F4F6; color: #111827; }
.bm-hidden-row { display: none; }
.bm-hidden-row.show { display: flex; }

.bm-textarea {
    width: 100%; border: 1px solid #D1D5DB; border-radius: 10px;
    padding: 12px 14px; font-size: 14px; font-family: inherit; color: #111827;
    resize: vertical; min-height: 96px; box-sizing: border-box;
}
.bm-textarea:focus { outline: none; border-color: #1A3B32; box-shadow: 0 0 0 3px rgba(26,59,50,0.12); }
.bm-textarea::placeholder { color: #9CA3AF; }

.bm-grid3 { display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 18px; margin-bottom: 28px; }
.bm-grid3 .bm-k { font-size: 11px; text-transform: uppercase; letter-spacing: 0.3px; color: #9CA3AF; font-weight: 600; margin-bottom: 4px; }
.bm-grid3 .bm-v { font-size: 13px; color: #111827; font-weight: 500; word-break: break-word; }
.bm-grid3 .bm-v a { color: #2563EB; text-decoration: none; }
.bm-grid3 .bm-v a:hover { text-decoration: underline; }
.bm-col { margin-bottom: 16px; }

/* Sidebar */
.bm-side-nav { display: flex; align-items: center; justify-content: space-between; padding: 14px 18px; border-bottom: 1px solid #EEF0F2; }
.bm-nav-group { display: flex; gap: 6px; }
.bm-nav-btn {
    width: 30px; height: 30px; border-radius: 8px; border: 1px solid #D1D5DB; background: #fff;
    color: #374151; display: inline-flex; align-items: center; justify-content: center;
    cursor: pointer; transition: all 0.15s; font-family: inherit; font-size: 15px;
}
.bm-nav-btn:hover { border-color: #1A3B32; color: #1A3B32; }
.bm-nav-btn:disabled { opacity: 0.4; cursor: not-allowed; }
.bm-side-label { font-size: 11px; text-transform: uppercase; letter-spacing: 0.4px; font-weight: 700; color: #9CA3AF; margin: 4px 0 8px; }

.bm-action {
    display: flex; align-items: center; gap: 10px; width: 100%;
    padding: 10px 12px; border-radius: 10px; border: none; background: none;
    font-size: 13px; font-weight: 500; color: #374151; cursor: pointer;
    font-family: inherit; text-align: left; text-decoration: none; transition: background 0.15s;
}
.bm-action:hover { background: #EEF1F0; color: #111827; }
.bm-action.danger:hover { background: #FEF2F2; color: #DC2626; }
.bm-action .bm-crown { margin-left: auto; }
.bm-action svg { width: 16px; height: 16px; color: #6B7280; flex-shrink: 0; }
.bm-action.danger svg { color: #DC2626; }

.bm-resend {
    background: #F3F4F6; border: 1px solid #EEF0F2; border-radius: 12px; padding: 14px; margin-top: 12px;
}
.bm-resend-head { display: flex; align-items: center; gap: 10px; }
.bm-resend-icon {
    width: 32px; height: 32px; border-radius: 9px; background: #fff; border: 1px solid #E5E7EB;
    display: inline-flex; align-items: center; justify-content: center; color: #1A3B32; flex-shrink: 0;
}
.bm-resend-icon svg { width: 15px; height: 15px; }
.bm-resend-title { font-size: 13px; font-weight: 600; color: #111827; display: flex; align-items: center; gap: 6px; }
.bm-resend-sub { font-size: 12px; color: #6B7280; margin-top: 6px; }

.bm-timeline {
    display: none; background: #fff; border: 1px solid #E5E7EB; border-radius: 10px;
    padding: 12px; margin-top: 8px; font-size: 12px; color: #6B7280;
}
.bm-timeline.open { display: block; }
.bm-saved { display: none; font-size: 12px; color: #15803D; font-weight: 600; }
.bm-saved.show { display: inline; }
.bm-feedback { display: none; font-size: 12px; color: #15803D; font-weight: 600; }
.bm-feedback.show { display: block; }

@media (max-width: 900px) {
    .bm-modal { flex-direction: column; height: 92vh; }
    .bm-main { width: 100%; }
    .bm-side { width: 100%; border-left: none; border-top: 1px solid #EEF0F2; max-height: 40%; }
    .bm-grid3 { grid-template-columns: 1fr; }
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
                <tr onclick="openBookingModal({{ $b->id }})" style="cursor:pointer;">
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
                        <div class="bk-menu" onclick="event.stopPropagation();">
                            <button class="bk-action" onclick="toggleMenu(this)" aria-label="Actions">
                                <svg viewBox="0 0 24 24" fill="currentColor"><circle cx="12" cy="5" r="1.5"/><circle cx="12" cy="12" r="1.5"/><circle cx="12" cy="19" r="1.5"/></svg>
                            </button>
                            <div class="bk-menu-drop">
                                <a href="#" class="menu-item-details" tabindex="0" onclick="openBookingModal({{ $b->id }}); return false;">
                                    <span class="m-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/><polyline points="9 15 11 17 15 13"/></svg></span>
                                    Details
                                </a>
                                <a href="{{ $b->service ? route('booking.service', $b->service) : route('booking.index') }}">
                                    <span class="m-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="23 4 23 10 17 10"/><path d="M20.49 15a9 9 0 1 1-2.12-9.36L23 10"/></svg></span>
                                    Rebook
                                    <span class="crown-badge"><svg viewBox="0 0 24 24" fill="currentColor"><path d="M2 18h20l-1.5-2H3.5L2 18zM4 16l1.5-8L10 11l2-5 2 5 4.5-3L20 16H4z"/></svg></span>
                                </a>
                                <a href="{{ $b->service ? route('booking.service', $b->service) : route('booking.index') }}">
                                    <span class="m-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="17 1 21 5 17 9"/><path d="M3 11V9a4 4 0 0 1 4-4h14"/><polyline points="7 23 3 19 7 15"/><path d="M21 13v2a4 4 0 0 1-4 4H3"/></svg></span>
                                    Reschedule
                                </a>
                                <form method="POST" action="{{ route('admin.booking.update', $b) }}" onsubmit="return confirm('Cancel this booking?')">
                                    @csrf @method('PATCH')
                                    <input type="hidden" name="status" value="cancelled">
                                    <button type="submit" class="danger">
                                        <span class="m-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/><line x1="9" y1="14" x2="15" y2="20"/><line x1="15" y1="14" x2="9" y2="20"/></svg></span>
                                        Cancel
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

@php
    $bookingData = [];
    foreach ($bookings as $b) {
        $dur = $b->service->duration_minutes ?? 30;
        $start = \Carbon\Carbon::parse($b->booking_time);
        $end = $start->copy()->addMinutes($dur);
        $acc = in_array($b->status, ['confirmed', 'completed']);
        $can = in_array($b->status, ['cancelled', 'no_show']);
        if ($acc) { $bl = 'Accepted'; $bc = 'accepted'; }
        elseif ($can) { $bl = $b->status === 'no_show' ? 'No Show' : 'Cancelled'; $bc = 'cancelled'; }
        else { $bl = 'Undecided'; $bc = 'undecided'; }
        $parts = preg_split('/\s+/', trim($b->customer_name), 2);
        $bookingData[] = [
            'id' => $b->id,
            'title' => $b->customer_name . ' and 56\'30 Studio Cafe',
            'first' => $parts[0] ?? $b->customer_name,
            'last' => $parts[1] ?? '',
            'email' => $b->customer_email,
            'phone' => $b->customer_phone ?? '',
            'statusLabel' => $bl,
            'statusClass' => $bc,
            'dateFull' => $b->booking_date->format('l, F jS, Y'),
            'duration' => $dur >= 60 ? floor($dur/60).' hour'.($dur>=120?'s':'').($dur%60 ? ' '.($dur%60).' minutes' : '') : $dur.' minutes',
            'startTime' => $start->format('g:i A'),
            'endTime' => $end->format('g:i A'),
            'startISO' => $b->booking_date->format('M j, Y') . ', ' . $start->format('g:i A'),
            'endISO' => $b->booking_date->format('M j, Y') . ', ' . $end->format('g:i A'),
            'created' => $b->created_at?->format('M j, Y, g:i A') ?? '',
            'service' => $b->service->name ?? 'Booking',
            'price' => number_format($b->total_amount ?? 0, 2),
            'maxPax' => $b->num_pax,
            'ref' => $b->booking_ref,
            'serviceUrl' => $b->service ? route('booking.service', $b->service) : route('booking.index'),
            'notes' => $b->internal_notes ?? '',
            'paymentMethod' => strtoupper($b->payment_method ?? 'N/A'),
            'paymentStatus' => ucfirst($b->payment_status ?? 'unpaid'),
            'requests' => $b->special_requests ?? '',
        ];
    }
@endphp
<script>window.__bookingData = @json($bookingData);</script>

{{-- Booking Details Modal --}}
<div id="bookingModal" class="bm-overlay" style="display:none;">
    <div class="bm-backdrop" onclick="closeBookingModal()"></div>
    <div class="bm-modal" role="dialog" aria-modal="true" aria-label="Booking details">
        {{-- Left main panel --}}
        <div class="bm-main">
            <div class="bm-label">Booking Details</div>
            <h2 class="bm-title" id="bm-title">Booking</h2>

            <div class="bm-hero">
                <div class="bm-hero-row">
                    <span class="badge-status" id="bm-status">Accepted</span>
                    <span style="font-size:13px;color:#6B7280;" id="bm-service"></span>
                </div>
                <div class="bm-hero-row">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                    <span id="bm-date"></span>
                </div>
                <div class="bm-hero-row">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                    <span id="bm-time"></span>
                </div>
                <div class="bm-hero-row">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
                    <a class="bm-link" href="https://maps.app.goo.gl/ZFzEivayWr5XDw2Z9" target="_blank" rel="noopener">https://maps.app.goo.gl/ZFzEivayWr5XDw2Z9</a>
                </div>
            </div>

            <div class="bm-section-title">Booking form details</div>
            <div class="bm-data-card">
                <div class="bm-row">
                    <span class="bm-k">Email</span>
                    <span class="bm-v"><span id="bm-email"></span><a href="#" class="bm-edit-link" onclick="return false;">Edit</a></span>
                </div>
                <div class="bm-row">
                    <span class="bm-k">First name</span>
                    <span class="bm-v"><span id="bm-first"></span></span>
                </div>
                <div class="bm-row">
                    <span class="bm-k">Last name</span>
                    <span class="bm-v"><span id="bm-last"></span></span>
                </div>
                <div class="bm-row">
                    <span class="bm-k">Price</span>
                    <span class="bm-v" id="bm-price"></span>
                </div>
                <div class="bm-row">
                    <span class="bm-k">Maximum group size <span class="bm-tooltip" title="Maximum number of participants allowed for this session.">&#9432;</span></span>
                    <span class="bm-v"><span id="bm-maxpax"></span><a href="#" class="bm-edit-link" onclick="return false;">Edit</a></span>
                </div>
                <div class="bm-row bm-hidden-row" id="bm-row-phone">
                    <span class="bm-k">Phone</span>
                    <span class="bm-v" id="bm-phone"></span>
                </div>
                <div class="bm-row bm-hidden-row" id="bm-row-pay">
                    <span class="bm-k">Payment</span>
                    <span class="bm-v"><span id="bm-paymethod"></span> &middot; <span id="bm-paystatus"></span></span>
                </div>
                <div class="bm-row bm-hidden-row" id="bm-row-requests">
                    <span class="bm-k">Special requests</span>
                    <span class="bm-v" id="bm-requests"></span>
                </div>
                <button type="button" class="bm-show-more" onclick="toggleShowMore()">Show more <span id="bm-more-arrow">&darr;</span></button>
            </div>

            <div class="bm-section-title">Notes <span class="bm-tooltip" title="Internal notes are only visible to staff.">&#9432;</span> <span class="bm-crown"><svg viewBox="0 0 24 24" fill="currentColor"><path d="M2 18h20l-1.5-2H3.5L2 18zM4 16l1.5-8L10 11l2-5 2 5 4.5-3L20 16H4z"/></svg></span></div>
            <textarea id="bm-notes" class="bm-textarea" placeholder="Write your note here..."></textarea>
            <div style="display:flex;align-items:center;gap:10px;margin-top:8px;">
                <button type="button" class="btn btn-primary btn-sm" onclick="saveNote()">Save note</button>
                <span class="bm-saved" id="bm-saved">Note saved.</span>
            </div>

            <div style="height:28px;"></div>
            <div class="bm-section-title">Additional details</div>
            <div class="bm-grid3">
                <div>
                    <div class="bm-col">
                        <div class="bm-k">Booking reference</div>
                        <div class="bm-v" id="bm-ref"></div>
                    </div>
                    <div class="bm-col">
                        <div class="bm-k">Booker timezone</div>
                        <div class="bm-v">Asia/Manila</div>
                    </div>
                </div>
                <div>
                    <div class="bm-col">
                        <div class="bm-k">Booking page</div>
                        <div class="bm-v"><a id="bm-page" href="{{ url('/') }}" target="_blank">5630studiocafe</a></div>
                    </div>
                    <div class="bm-col">
                        <div class="bm-k">Booker start time</div>
                        <div class="bm-v" id="bm-start"></div>
                    </div>
                </div>
                <div>
                    <div class="bm-col">
                        <div class="bm-k">Created on</div>
                        <div class="bm-v" id="bm-created"></div>
                    </div>
                    <div class="bm-col">
                        <div class="bm-k">Booker end time</div>
                        <div class="bm-v" id="bm-end"></div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Right sidebar --}}
        <div class="bm-side">
            <div class="bm-side-nav">
                <div class="bm-nav-group">
                    <button class="bm-nav-btn" id="bm-prev" onclick="stepBooking(-1)" aria-label="Previous booking">&lsaquo;</button>
                    <button class="bm-nav-btn" id="bm-next" onclick="stepBooking(1)" aria-label="Next booking">&rsaquo;</button>
                </div>
                <span style="font-size:12px;color:#9CA3AF;font-weight:600;" id="bm-counter"></span>
                <button class="bm-nav-btn" onclick="closeBookingModal()" aria-label="Close">&times;</button>
            </div>

            <div class="bm-side-scroll">
                <div class="bm-side-label">Actions</div>
                <a class="bm-action" id="bm-rebook" href="#">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="23 4 23 10 17 10"/><path d="M20.49 15a9 9 0 1 1-2.12-9.36L23 10"/></svg>
                    Rebook
                    <span class="bm-crown"><svg viewBox="0 0 24 24" fill="currentColor"><path d="M2 18h20l-1.5-2H3.5L2 18zM4 16l1.5-8L10 11l2-5 2 5 4.5-3L20 16H4z"/></svg></span>
                </a>
                <a class="bm-action" id="bm-reschedule" href="#">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="17 1 21 5 17 9"/><path d="M3 11V9a4 4 0 0 1 4-4h14"/><polyline points="7 23 3 19 7 15"/><path d="M21 13v2a4 4 0 0 1-4 4H3"/></svg>
                    Reschedule
                </a>
                <button class="bm-action" onclick="cancelBooking()">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/><line x1="9" y1="14" x2="15" y2="20"/><line x1="15" y1="14" x2="9" y2="20"/></svg>
                    Cancel
                </button>
                <button class="bm-action danger" onclick="deleteBooking()">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/><line x1="10" y1="11" x2="10" y2="17"/><line x1="14" y1="11" x2="14" y2="17"/></svg>
                    Delete
                </button>
                <button class="bm-action" onclick="toggleTimeline()">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/><polyline points="10 9 9 9 8 9"/></svg>
                    Notifications timeline
                </button>
                <div class="bm-timeline" id="bm-timeline">No notifications have been sent for this booking yet.</div>

                <div class="bm-resend">
                    <div class="bm-resend-head">
                        <span class="bm-resend-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg></span>
                        <div>
                            <div class="bm-resend-title">Resend notifications <span class="bm-crown"><svg viewBox="0 0 24 24" fill="currentColor"><path d="M2 18h20l-1.5-2H3.5L2 18zM4 16l1.5-8L10 11l2-5 2 5 4.5-3L20 16H4z"/></svg></span></div>
                        </div>
                    </div>
                    <div class="bm-resend-sub">Resend emails, SMS, and webhooks</div>
                    <div style="margin-top:12px;">
                        <button type="button" class="btn btn-primary btn-sm" style="width:100%;" onclick="resendNotifications()">Resend now</button>
                    </div>
                    <div class="bm-feedback" id="bm-feedback">Notifications resent successfully.</div>
                </div>
            </div>
        </div>
    </div>
</div>

<form method="POST" id="bm-cancel-form" style="display:none;">
    @csrf @method('PATCH')
    <input type="hidden" name="status" value="cancelled">
</form>
<form method="POST" id="bm-delete-form" style="display:none;">
    @csrf @method('DELETE')
</form>

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

/* ── Booking Details Modal ── */
const bookingList = window.__bookingData || [];
let bookingIndex = -1;
const adminBookingsBase = '{{ url('admin/bookings') }}';

function openBookingModal(id) {
    bookingIndex = bookingList.findIndex(b => b.id === id);
    if (bookingIndex < 0) return;
    renderBooking();
    document.getElementById('bookingModal').style.display = 'flex';
    document.body.style.overflow = 'hidden';
    document.getElementById('bm-notes').focus();
}

function closeBookingModal() {
    document.getElementById('bookingModal').style.display = 'none';
    document.body.style.overflow = '';
}

function stepBooking(dir) {
    if (bookingList.length <= 1) return;
    bookingIndex = (bookingIndex + dir + bookingList.length) % bookingList.length;
    renderBooking();
}

function renderBooking() {
    const b = bookingList[bookingIndex];
    if (!b) return;

    document.getElementById('bm-title').textContent = b.title;

    const badge = document.getElementById('bm-status');
    badge.className = 'badge-status ' + b.statusClass;
    badge.textContent = b.statusLabel;
    document.getElementById('bm-service').textContent = b.service;

    document.getElementById('bm-date').textContent = b.dateFull;
    document.getElementById('bm-time').textContent = b.duration + ' \u2022 ' + b.startTime + ' \u2014 ' + b.endTime + ' (Asia/Manila)';

    document.getElementById('bm-email').textContent = b.email;
    document.getElementById('bm-first').textContent = b.first;
    document.getElementById('bm-last').textContent = b.last;
    document.getElementById('bm-price').textContent = '\u20B1' + b.price;
    document.getElementById('bm-maxpax').textContent = b.maxPax;

    document.getElementById('bm-phone').textContent = b.phone || '\u2014';
    document.getElementById('bm-paymethod').textContent = b.paymentMethod;
    document.getElementById('bm-paystatus').textContent = b.paymentStatus;
    document.getElementById('bm-requests').textContent = b.requests || '\u2014';

    document.getElementById('bm-ref').textContent = b.ref;
    document.getElementById('bm-start').textContent = b.startISO;
    document.getElementById('bm-created').textContent = b.created;
    document.getElementById('bm-end').textContent = b.endISO;

    document.getElementById('bm-rebook').href = b.serviceUrl;
    document.getElementById('bm-reschedule').href = b.serviceUrl;

    document.getElementById('bm-notes').value = b.notes;
    document.getElementById('bm-saved').classList.remove('show');

    const prev = document.getElementById('bm-prev');
    const next = document.getElementById('bm-next');
    const single = bookingList.length <= 1;
    prev.disabled = single;
    next.disabled = single;
    document.getElementById('bm-counter').textContent = single ? '' : (bookingIndex + 1) + ' / ' + bookingList.length;
}

function cancelBooking() {
    const b = bookingList[bookingIndex];
    if (!b || !confirm('Cancel this booking?')) return;
    const form = document.getElementById('bm-cancel-form');
    form.action = adminBookingsBase + '/' + b.id + '/status';
    form.submit();
}

function deleteBooking() {
    const b = bookingList[bookingIndex];
    if (!b || !confirm('Permanently delete this booking?')) return;
    const form = document.getElementById('bm-delete-form');
    form.action = adminBookingsBase + '/' + b.id;
    form.submit();
}

function saveNote() {
    const b = bookingList[bookingIndex];
    if (!b) return;
    fetch(adminBookingsBase + '/' + b.id + '/note', {
        method: 'PATCH',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content
        },
        body: JSON.stringify({ internal_notes: document.getElementById('bm-notes').value })
    }).then(function (r) {
        if (!r.ok) throw new Error('save failed');
        document.getElementById('bm-saved').classList.add('show');
    }).catch(function () {
        alert('Could not save the note. Please try again.');
    });
}

function toggleTimeline() {
    document.getElementById('bm-timeline').classList.toggle('open');
}

function resendNotifications() {
    const fb = document.getElementById('bm-feedback');
    fb.classList.add('show');
    fb.textContent = 'Notifications resent successfully.';
    setTimeout(function () { fb.classList.remove('show'); }, 3000);
}

function toggleShowMore() {
    const hidden = document.querySelectorAll('.bm-hidden-row');
    const arrow = document.getElementById('bm-more-arrow');
    const showing = hidden[0] && hidden[0].classList.contains('show');
    hidden.forEach(function (r) { r.classList.toggle('show', !showing); });
    arrow.textContent = showing ? '\u2193' : '\u2191';
}

document.addEventListener('keydown', function (e) {
    if (e.key === 'Escape' && document.getElementById('bookingModal').style.display === 'flex') {
        closeBookingModal();
    }
    if (e.key === 'ArrowRight' && document.getElementById('bookingModal').style.display === 'flex') {
        stepBooking(1);
    }
    if (e.key === 'ArrowLeft' && document.getElementById('bookingModal').style.display === 'flex') {
        stepBooking(-1);
    }
});
</script>
@endsection
