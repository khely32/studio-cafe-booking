@extends('admin.layout')
@section('title', 'Analytics')

@section('styles')
<style>
/* ── Page shell ── */
.an-page {
    padding: 28px 32px;
    background: #F5F2EC;
    min-height: calc(100vh - 64px);
}

/* ── Header ── */
.an-header {
    display: flex; align-items: center; justify-content: space-between;
    margin-bottom: 24px; gap: 16px; flex-wrap: wrap;
}
.an-header-left { display: flex; align-items: center; gap: 12px; }
.an-header-left h1 { font-size: 26px; font-weight: 800; color: #2C221E; letter-spacing: -0.4px; }
.an-header-left .an-date { font-size: 13px; color: #7A6E65; }
.an-beta {
    display: inline-flex; align-items: center; gap: 5px;
    padding: 4px 12px; border-radius: 9999px;
    background: rgba(194,155,56,0.14); color: #A37B2C;
    border: 1px solid rgba(194,155,56,0.30);
    font-size: 10px; font-weight: 800; letter-spacing: 0.12em; text-transform: uppercase;
}
.an-beta svg { width: 11px; height: 11px; }

.an-time-group {
    display: flex; align-items: center;
    border: 1px solid #E3DAC9; border-radius: 100px;
    background: #FAF8F5; overflow: hidden; box-shadow: 0 1px 3px rgba(44,34,30,0.06);
}
.an-time-group .tg-btn {
    padding: 9px 18px; font-size: 12px; font-weight: 600;
    background: transparent; border: none; color: #7A6E65;
    cursor: pointer; font-family: inherit;
    transition: all 0.15s; white-space: nowrap; position: relative;
}
.an-time-group .tg-btn:not(:last-child) { border-right: 1px solid #E3DAC9; }
.an-time-group .tg-btn:hover { color: #2C221E; background: #F0EAE1; }
.an-time-group .tg-btn.active { color: #2C221E; font-weight: 700; }
.an-time-group .tg-btn.active::after {
    content: ''; position: absolute; bottom: 4px; left: 50%; transform: translateX(-50%);
    width: 14px; height: 2px; border-radius: 2px; background: #A37B2C;
}

/* ── Hero banner ── */
.an-hero {
    position: relative; overflow: hidden;
    background: linear-gradient(135deg, #2C221E 0%, #3D302A 55%, #4A3820 100%);
    border: 1px solid rgba(194,155,56,0.35);
    border-radius: 24px; padding: 24px 28px; margin-bottom: 24px;
    color: #E8DCC4; display: flex; align-items: center; justify-content: space-between;
    gap: 16px; flex-wrap: wrap;
    box-shadow: 0 16px 40px rgba(44,34,30,0.22);
}
.an-hero .an-hero-glow {
    position: absolute; top: -60px; right: -60px; width: 200px; height: 200px;
    border-radius: 50%; background: rgba(194,155,56,0.18); filter: blur(48px); pointer-events: none;
}
.an-hero-left { display: flex; align-items: center; gap: 16px; position: relative; z-index: 1; }
.an-hero-icon {
    width: 46px; height: 46px; border-radius: 14px;
    background: rgba(194,155,56,0.18); color: #D4AF37;
    display: flex; align-items: center; justify-content: center;
    border: 1px solid rgba(194,155,56,0.35); flex-shrink: 0;
}
.an-hero-icon svg { width: 22px; height: 22px; }
.an-hero h2 { font-size: 16px; font-weight: 800; color: #FAF8F5; }
.an-hero p { font-size: 13px; color: #C9BEB0; margin-top: 2px; }
.an-hero p b { color: #D4AF37; font-weight: 700; }

.an-hero-stats { display: flex; gap: 12px; position: relative; z-index: 1; flex-wrap: wrap; }
.an-hero-stat {
    min-width: 150px;
    background: rgba(250,248,245,0.06);
    border: 1px solid rgba(227,218,201,0.15);
    border-radius: 16px; padding: 12px 16px;
}
.an-hero-stat .hs-label { font-size: 10px; font-weight: 700; letter-spacing: 0.1em; text-transform: uppercase; color: #A7998A; }
.an-hero-stat .hs-value { font-size: 14px; font-weight: 800; color: #E8DCC4; margin-top: 2px; }
.an-hero-stat .hs-value.em { color: #D4AF37; }

/* ── Card base ── */
.an-card {
    background: #FAF8F5;
    border: 1px solid #E3DAC9;
    border-radius: 24px;
    padding: 20px;
    box-shadow: 0 2px 6px rgba(44,34,30,0.05);
    transition: all 0.25s ease;
    display: flex; flex-direction: column;
    position: relative;
}
.an-card:hover { box-shadow: 0 10px 28px rgba(44,34,30,0.10); transform: translateY(-2px); }
.an-card-hd {
    display: flex; align-items: center; justify-content: space-between;
    margin-bottom: 18px; gap: 10px;
}
.an-label {
    font-size: 10px; font-weight: 800;
    letter-spacing: 0.12em; text-transform: uppercase;
    color: #A37B2C;
}
.an-pill {
    font-size: 10px; font-weight: 700; color: #7A6E65;
    background: #F0EAE1; border: 1px solid #E3DAC9;
    padding: 3px 10px; border-radius: 9999px; white-space: nowrap;
}

/* inset tile */
.an-tile {
    background: rgba(240,234,225,0.6);
    border: 1px solid rgba(227,218,201,0.6);
    border-radius: 16px; padding: 14px;
}

/* ── Card: Meetings booked ── */
.an-kpi { display: flex; flex-direction: column; align-items: center; text-align: center; flex: 1; justify-content: center; }
.an-kpi-icon {
    width: 44px; height: 44px; border-radius: 14px;
    background: #F0EAE1; color: #2C221E;
    display: flex; align-items: center; justify-content: center; margin-bottom: 12px;
}
.an-kpi-icon svg { width: 20px; height: 20px; }
.an-kpi-value { font-size: 42px; font-weight: 800; color: #2C221E; letter-spacing: -1px; line-height: 1; }
.an-kpi-sub { font-size: 12px; color: #7A6E65; margin-top: 6px; }

/* ── Grids ── */
.an-grid {
    display: grid; grid-template-columns: 1fr 1fr 1fr;
    gap: 16px; margin-bottom: 16px;
}
.an-grid-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; }
.an-grid-3 { display: grid; grid-template-columns: 7fr 5fr; gap: 16px; }

/* status breakdown tiles */
.an-status-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 10px; }
.an-status {
    border-radius: 16px; padding: 14px;
    text-align: center; border: 1px solid transparent;
}
.an-status .st-label { font-size: 10px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.08em; }
.an-status .st-value { font-size: 20px; font-weight: 800; }
.an-status.success { background: rgba(16,185,129,0.10); border-color: rgba(16,185,129,0.25); color: #10B981; }
.an-status.warning { background: rgba(245,158,11,0.10); border-color: rgba(245,158,11,0.25); color: #F59E0B; }
.an-status.danger  { background: rgba(244,63,94,0.10);  border-color: rgba(244,63,94,0.25);  color: #F43F5E; }
.an-status.neutral { background: #F0EAE1; border-color: #E3DAC9; color: #7A6E65; }
.an-status .st-value span { font-size: 11px; font-weight: 700; opacity: 0.75; }

/* popular pages */
.an-pp { display: flex; flex-direction: column; gap: 10px; flex: 1; }
.an-pp-item { display: flex; align-items: center; gap: 12px; }
.an-pp-icon {
    width: 34px; height: 34px; border-radius: 10px;
    background: rgba(194,155,56,0.14); color: #A37B2C;
    display: flex; align-items: center; justify-content: center; flex-shrink: 0;
}
.an-pp-icon svg { width: 16px; height: 16px; }
.an-pp-info { flex: 1; min-width: 0; }
.an-pp-info .pp-name { font-size: 13px; font-weight: 700; color: #2C221E; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
.an-pp-info .pp-handle { font-size: 11px; color: #7A6E65; }
.an-pp-count {
    min-width: 28px; height: 28px; padding: 0 8px;
    background: #F0EAE1; color: #2C221E;
    border-radius: 9px; display: inline-flex; align-items: center; justify-content: center;
    font-size: 12px; font-weight: 800; flex-shrink: 0;
}

/* SMS card */
.an-sms { display: flex; flex-direction: column; justify-content: center; flex: 1; }
.an-sms-row { display: flex; align-items: center; gap: 14px; }
.an-kpi-icon.sm { width: 40px; height: 40px; margin-bottom: 0; }
.an-sms-value { font-size: 30px; font-weight: 800; color: #2C221E; letter-spacing: -0.5px; }
.an-sms-sub { font-size: 12px; color: #7A6E65; }

/* Amount received (featured) */
.an-amt { flex: 1; display: flex; flex-direction: column; justify-content: center; position: relative; z-index: 1; }
.an-amt .an-label-light { color: #A7998A; }
.an-amt-value { font-size: 40px; font-weight: 800; color: #FAF8F5; letter-spacing: -1px; line-height: 1.1; }
.an-amt-change {
    display: inline-flex; align-items: center; gap: 5px;
    margin-top: 8px; width: fit-content;
    background: rgba(16,185,129,0.14); color: #34D399;
    border: 1px solid rgba(16,185,129,0.30);
    padding: 5px 12px; border-radius: 9999px;
    font-size: 11px; font-weight: 800;
}
.an-amt-change svg { width: 13px; height: 13px; }
.an-amt .an-amt-icon {
    position: absolute; top: 18px; right: 18px; z-index: 2;
    width: 44px; height: 44px; border-radius: 14px;
    background: rgba(194,155,56,0.20); color: #D4AF37;
    display: flex; align-items: center; justify-content: center;
    border: 1px solid rgba(194,155,56,0.35);
}
.an-amt .an-amt-icon svg { width: 22px; height: 22px; }

/* Trend chart */
.an-chart { display: flex; align-items: flex-end; gap: 8px; flex: 1; padding-top: 8px; }
.an-bar-col { flex: 1; display: flex; flex-direction: column; align-items: center; gap: 6px; }
.an-bar {
    width: 100%; max-width: 28px; border-radius: 6px 6px 0 0;
    background: linear-gradient(180deg, #A37B2C, #C29B38);
    transition: height 0.4s ease; position: relative;
}
.an-bar-col .bar-val {
    font-size: 10px; font-weight: 700; color: #7A6E65; white-space: nowrap;
}
.an-bar-col .bar-month { font-size: 10px; color: #7A6E65; }

/* Weekday bars */
.an-dow { display: grid; grid-template-columns: repeat(7, 1fr); gap: 8px; flex: 1; padding-top: 8px; align-items: flex-end; }
.an-dow-col { display: flex; flex-direction: column; align-items: center; gap: 6px; }
.an-dow .an-bar { background: linear-gradient(180deg, #3D302A, #2C221E); }
.an-dow .an-bar.hot { background: linear-gradient(180deg, #D4AF37, #A37B2C); }
.an-dow .bar-day { font-size: 10px; color: #7A6E65; }

/* ── Top selling products (horizontal bars) ── */
.an-tp { display: flex; flex-direction: column; gap: 14px; }
.an-tp-row { display: flex; align-items: center; gap: 14px; }
.an-tp-main { flex: 1; min-width: 0; }
.an-tp-top { display: flex; align-items: center; gap: 8px; margin-bottom: 6px; }
.an-tp-name {
    font-size: 13px; font-weight: 700; color: #2C221E;
    white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
}
.an-tp-tag {
    font-size: 9px; font-weight: 800; letter-spacing: 0.08em; text-transform: uppercase;
    padding: 2px 8px; border-radius: 9999px; flex-shrink: 0;
    background: rgba(194,155,56,0.14); color: #A37B2C; border: 1px solid rgba(194,155,56,0.30);
}
.an-tp-tag.addon { background: #F0EAE1; color: #5C4A3A; border-color: #E3DAC9; }
.an-tp-track {
    height: 12px; border-radius: 9999px;
    background: linear-gradient(180deg, #F0E8DD, #EADFCB);
    border: 1px solid rgba(44,34,30,0.06);
    overflow: hidden;
}
.an-tp-bar {
    height: 100%; border-radius: 9999px;
    background: linear-gradient(90deg, #8A6520, #C29B38);
    box-shadow: inset 0 1px 0 rgba(255,255,255,0.25);
    position: relative; overflow: hidden;
    transition: width 0.6s cubic-bezier(0.4,0,0.2,1);
    animation: tpGrow 0.7s cubic-bezier(0.4,0,0.2,1) both;
}
.an-tp-bar.gold { background: linear-gradient(90deg, #A37B2C, #D4AF37); }
.an-tp-bar::after {
    content: ''; position: absolute; inset: 0;
    background: linear-gradient(90deg, transparent, rgba(255,255,255,0.35), transparent);
    transform: translateX(-100%);
    animation: tpShine 3s ease-in-out 1s infinite;
}
@keyframes tpGrow { from { width: 0; } }
@keyframes tpShine { 0% { transform: translateX(-100%); } 60%, 100% { transform: translateX(100%); } }
.an-tp-nums { display: flex; align-items: center; gap: 10px; flex-shrink: 0; min-width: 150px; justify-content: flex-end; }
.an-tp-units { font-size: 16px; font-weight: 800; color: #2C221E; white-space: nowrap; }
.an-tp-rev {
    font-size: 11px; font-weight: 700; color: #8A6520;
    background: rgba(194,155,56,0.12); border: 1px solid rgba(194,155,56,0.22);
    padding: 3px 10px; border-radius: 9999px; white-space: nowrap;
}
.an-tp-medal { flex-shrink: 0; }
.an-tp-medal svg { width: 18px; height: 18px; }
.an-tp-medal.rank-0 { color: #D4AF37; }
.an-tp-medal.rank-1 { color: #9C8E7C; }
.an-tp-medal.rank-2 { color: #C29B38; }

@keyframes fadeUp { from { opacity: 0; transform: translateY(14px); } to { opacity: 1; transform: translateY(0); } }
.anim { animation: fadeUp 0.45s cubic-bezier(0.4,0,0.2,1) both; }
.anim-d1 { animation-delay: 0.05s; }
.anim-d2 { animation-delay: 0.10s; }
.anim-d3 { animation-delay: 0.15s; }
.anim-d4 { animation-delay: 0.20s; }
.anim-d5 { animation-delay: 0.25s; }

@media (max-width: 1100px) {
    .an-page { padding: 20px 16px; }
    .an-grid { grid-template-columns: 1fr 1fr; }
    .an-grid > :last-child, .an-grid > :first-child { grid-column: span 1; }
    .an-grid-2, .an-grid-3 { grid-template-columns: 1fr; }
}
@media (max-width: 700px) {
    .an-grid { grid-template-columns: 1fr; }
    .an-header { flex-direction: column; align-items: flex-start; }
    .an-hero-stats { width: 100%; }
    .an-hero-stat { min-width: calc(50% - 6px); flex: 1; }
    .an-tp-nums { min-width: 0; }
    .an-tp-row { flex-wrap: wrap; gap: 8px; }
    .an-tp-nums { width: 100%; justify-content: flex-start; }
}
</style>
@endsection

@section('content')
<div class="an-page">

    {{-- Header --}}
    <div class="an-header anim anim-d1">
        <div class="an-header-left">
            <h1>Analytics</h1>
            <span class="an-beta">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 3l1.9 5.8a2 2 0 0 0 1.3 1.3L21 12l-5.8 1.9a2 2 0 0 0-1.3 1.3L12 21l-1.9-5.8a2 2 0 0 0-1.3-1.3L3 12l5.8-1.9a2 2 0 0 0 1.3-1.3z"/></svg>
                Beta
            </span>
            <span class="an-date">{{\Carbon\Carbon::today()->format('M d, Y')}}</span>
        </div>
        <div class="an-time-group">
            <button class="tg-btn active">Yesterday</button>
            <button class="tg-btn">Last 7 days</button>
            <button class="tg-btn">Last 30 days</button>
        </div>
    </div>

    {{-- Executive hero --}}
    <div class="an-hero anim anim-d2">
        <div class="an-hero-glow"></div>
        <div class="an-hero-left">
            <div class="an-hero-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 3l1.9 5.8a2 2 0 0 0 1.3 1.3L21 12l-5.8 1.9a2 2 0 0 0-1.3 1.3L12 21l-1.9-5.8a2 2 0 0 0-1.3-1.3L3 12l5.8-1.9a2 2 0 0 0 1.3-1.3z"/></svg>
            </div>
            <div>
                <h2>You're on a roll{{ auth()->user()?->name ? ', ' . auth()->user()->name : '' }}!</h2>
                <p>You saved <b>1 hour</b> and avoided <b>15 redundant emails</b> yesterday.</p>
            </div>
        </div>
        <div class="an-hero-stats">
            @php
                $topPkg = ($packageBreakdown ?? collect())->first();
            @endphp
            <div class="an-hero-stat">
                <div class="hs-label">Top Category</div>
                <div class="hs-value em">{{ $topPkg->name ?? 'Pre-Birthday Shoots' }}</div>
            </div>
            <div class="an-hero-stat">
                <div class="hs-label">Revenue Run-Rate</div>
                <div class="hs-value">₱{{ number_format($overview['total_revenue'] ?? 0, 0) }} collected</div>
            </div>
            <div class="an-hero-stat">
                <div class="hs-label">Expenses</div>
                <div class="hs-value">₱{{ number_format($totalExpenses ?? 0, 0) }} spent</div>
            </div>
            <div class="an-hero-stat">
                <div class="hs-label">Net Profit</div>
                <div class="hs-value em">₱{{ number_format($netProfit ?? 0, 0) }}</div>
            </div>
            <div class="an-hero-stat">
                <div class="hs-label">Response Efficiency</div>
                <div class="hs-value em">100% Automated</div>
            </div>
        </div>
    </div>

    {{-- Row 1: 3 cards --}}
    <div class="an-grid anim anim-d3">

        {{-- KPI: Meetings booked --}}
        <div class="an-card">
            <div class="an-card-hd">
                <span class="an-label">Meetings Booked</span>
            </div>
            <div class="an-kpi">
                <div class="an-kpi-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                </div>
                <div class="an-kpi-value">{{ $overview['today_bookings'] ?? 0 }}</div>
                <div class="an-kpi-sub">Recorded yesterday</div>
            </div>
        </div>

        {{-- Booking status breakdown --}}
        @php
            $all = array_sum($statusBreakdown ?? []);
            $confirmed = ($statusBreakdown['confirmed'] ?? 0) + ($statusBreakdown['completed'] ?? 0);
            $cancelled = $statusBreakdown['cancelled'] ?? 0;
            $rescheduled = $statusBreakdown['rescheduled'] ?? 0;
            $noShow = $statusBreakdown['no_show'] ?? 0;
            $total = $all ?: 1;
            $confirmedPct = round(($confirmed / $total) * 100);
            $cancelledPct = round(($cancelled / $total) * 100);
            $rescheduledPct = round(($rescheduled / $total) * 100);
            $noShowPct = round(($noShow / $total) * 100);
        @endphp
        <div class="an-card">
            <div class="an-card-hd">
                <span class="an-label">Booking Status Breakdown</span>
                <span class="an-pill">Ratio / Count</span>
            </div>
            <div class="an-status-grid" style="flex:1;">
                <div class="an-status success">
                    <div class="st-label">Completed</div>
                    <div class="st-value">{{ $confirmedPct }}% <span>({{ $confirmed }})</span></div>
                </div>
                <div class="an-status warning">
                    <div class="st-label">Rescheduled</div>
                    <div class="st-value">{{ $rescheduledPct }}% <span>({{ $rescheduled }})</span></div>
                </div>
                <div class="an-status danger">
                    <div class="st-label">Cancelled</div>
                    <div class="st-value">{{ $cancelledPct }}% <span>({{ $cancelled }})</span></div>
                </div>
                <div class="an-status neutral">
                    <div class="st-label">No Show</div>
                    <div class="st-value">{{ $noShowPct }}% <span>({{ $noShow }})</span></div>
                </div>
            </div>
        </div>

        {{-- Popular pages --}}
        <div class="an-card">
            <div class="an-card-hd">
                <span class="an-label">Popular Booking Pages</span>
                <span class="an-pill">Top 3</span>
            </div>
            <div class="an-pp">
                @forelse(($packageBreakdown ?? collect())->take(3) as $pkg)
                <div class="an-pp-item">
                    <div class="an-pp-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="8" r="7"/><polyline points="8.21 13.89 7 23 12 20 17 23 15.79 13.88"/></svg>
                    </div>
                    <div class="an-pp-info">
                        <div class="pp-name">{{ $pkg->name ?? 'Studio Cafe' }}</div>
                        <div class="pp-handle">/{{ strtolower(str_replace(' ','',$pkg->name ?? 'studio')) }}cafe</div>
                    </div>
                    <span class="an-pp-count">{{ $pkg->count ?? 0 }}</span>
                </div>
                @empty
                <div class="an-pp-item">
                    <div class="an-pp-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="8" r="7"/><polyline points="8.21 13.89 7 23 12 20 17 23 15.79 13.88"/></svg>
                    </div>
                    <div class="an-pp-info">
                        <div class="pp-name">56'30 Studio Cafe</div>
                        <div class="pp-handle">/5630studiocafe</div>
                    </div>
                    <span class="an-pp-count">{{ $overview['today_bookings'] ?? 0 }}</span>
                </div>
                @endforelse
            </div>
        </div>

    </div>

    {{-- Row 2: lower KPI cards --}}
    <div class="an-grid anim anim-d4">

        {{-- SMS sent --}}
        <div class="an-card">
            <div class="an-card-hd">
                <span class="an-label">SMS Reminders Sent</span>
                <span class="an-pill">Automated</span>
            </div>
            <div class="an-sms">
                <div class="an-sms-row">
                    <div class="an-kpi-icon sm">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
                    </div>
                    <div>
                        <div class="an-sms-value">0</div>
                        <div class="an-sms-sub">SMS sent yesterday</div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Total amount received (featured) --}}
        <div class="an-card" style="background:linear-gradient(135deg,#2C221E 0%,#3D302A 100%);border-color:rgba(194,155,56,0.35);">
            <div class="an-amt-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
            </div>
            <div class="an-card-hd" style="margin-bottom:8px;">
                <span class="an-label an-label-light">Total Amount Received</span>
            </div>
            <div class="an-amt">
                <div class="an-amt-value">₱{{ number_format($overview['total_revenue'] ?? 0, 0) }}</div>
                <span class="an-amt-change">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"/><polyline points="19 12 12 19 5 12"/></svg>
                    Net profit: ₱{{ number_format($netProfit ?? 0, 0) }}
                </span>
            </div>
        </div>

        {{-- Net profit this month (featured) --}}
        <div class="an-card" style="background:linear-gradient(135deg,#A37B2C 0%,#C29B38 100%);border-color:rgba(44,34,30,0.35);">
            <div class="an-amt-icon" style="background:rgba(44,34,30,0.20);color:#2C221E;border-color:rgba(44,34,30,0.35);">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"/></svg>
            </div>
            <div class="an-card-hd" style="margin-bottom:8px;">
                <span class="an-label an-label-light" style="color:#2C221E;">Net Profit This Month</span>
            </div>
            <div class="an-amt">
                <div class="an-amt-value" style="color:#2C221E;">₱{{ number_format($monthNetProfit ?? 0, 0) }}</div>
                <span class="an-amt-change" style="background:rgba(44,34,30,0.14);color:#2C221E;border-color:rgba(44,34,30,0.30);">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                    Revenue ₱{{ number_format($overview['month_revenue'] ?? 0, 0) }} · Expenses ₱{{ number_format($monthExpenses ?? 0, 0) }}
                </span>
            </div>
        </div>

    </div>

    {{-- Row 3: trend + weekday --}}
    <div class="an-grid-3 anim anim-d5">

        {{-- Monthly bookings trend --}}
        <div class="an-card">
            <div class="an-card-hd">
                <span class="an-label">Booking Trend</span>
                <span class="an-pill">Last 12 months</span>
            </div>
            @php
                $maxCount = max(array_column($monthlyBookings, 'count')) ?: 1;
            @endphp
            <div class="an-chart">
                @foreach($monthlyBookings as $m)
                <div class="an-bar-col">
                    <span class="bar-val">{{ $m['count'] }}</span>
                    <div class="an-bar" style="height: {{ max(6, ($m['count'] / $maxCount) * 120) }}px;"></div>
                    <span class="bar-month">{{ $m['month'] }}</span>
                </div>
                @endforeach
            </div>
        </div>

        {{-- Bookings by weekday --}}
        <div class="an-card">
            <div class="an-card-hd">
                <span class="an-label">By Weekday</span>
                <span class="an-pill">Frequency</span>
            </div>
            @php
                $maxDow = max(array_column($bookingsByDay, 'count')) ?: 1;
            @endphp
            <div class="an-dow">
                @foreach($bookingsByDay as $d)
                <div class="an-dow-col">
                    <span class="bar-val">{{ $d['count'] }}</span>
                    <div class="an-bar {{ $d['count'] === $maxDow && $d['count'] > 0 ? 'hot' : '' }}" style="height: {{ max(6, ($d['count'] / $maxDow) * 120) }}px;"></div>
                    <span class="bar-day">{{ $d['day'] }}</span>
                </div>
                @endforeach
            </div>
        </div>

    </div>

    {{-- Row 4: Top selling products --}}
    <div class="an-card anim" style="animation-delay:0.30s;">
        <div class="an-card-hd" style="display:flex;align-items:center;justify-content:space-between;margin-bottom:20px;gap:10px;">
            <div style="display:flex;align-items:center;gap:10px;">
                <div style="width:34px;height:34px;border-radius:10px;background:rgba(194,155,56,0.14);color:#A37B2C;display:flex;align-items:center;justify-content:center;">
                    <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="20" x2="18" y2="10"/><line x1="12" y1="20" x2="12" y2="4"/><line x1="6" y1="20" x2="6" y2="14"/></svg>
                </div>
                <span class="an-label" style="font-size:11px;">Top Selling Products</span>
            </div>
            <span class="an-pill">By units sold · All time</span>
        </div>

        @php
            $maxUnits = ($topProducts ?? collect())->pluck('units')->max() ?: 1;
        @endphp

        <div class="an-tp">
            @forelse(($topProducts ?? []) as $i => $product)
            <div class="an-tp-row">
                <div class="an-tp-medal {{ $i < 3 ? 'rank-' . $i : '' }}">
                    @if($i < 3)
                    <svg viewBox="0 0 24 24" fill="currentColor" stroke="none"><path d="M12 2l2.4 4.9 5.4.8-3.9 3.8.9 5.4-4.8-2.5-4.8 2.5.9-5.4L4.2 7.7l5.4-.8L12 2z"/></svg>
                    @else
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="color:#C4B8A8;"><rect x="4" y="4" width="16" height="16" rx="2"/></svg>
                    @endif
                </div>
                <div class="an-tp-main">
                    <div class="an-tp-top">
                        <span class="an-tp-name">{{ $product['name'] }}</span>
                        <span class="an-tp-tag {{ $product['type'] === 'Add-On' ? 'addon' : '' }}">{{ $product['type'] }}</span>
                    </div>
                    <div class="an-tp-track">
                        <div class="an-tp-bar {{ $i === 0 ? 'gold' : '' }}" style="width: {{ max(2, ($product['units'] / $maxUnits) * 100) }}%;"></div>
                    </div>
                </div>
                <div class="an-tp-nums">
                    <span class="an-tp-units">{{ $product['units'] }} <span style="font-size:11px;font-weight:600;color:#7A6E65;">sold</span></span>
                    <span class="an-tp-rev">₱{{ number_format($product['revenue'], 0) }}</span>
                </div>
            </div>
            @empty
            <div class="an-pp-item">
                <div class="an-pp-info">
                    <div class="pp-name" style="font-size:13px;font-weight:700;color:#2C221E;">No confirmed sales yet</div>
                    <div class="pp-handle" style="font-size:11px;color:#7A6E65;">Sales charts will appear once bookings are confirmed or completed.</div>
                </div>
            </div>
            @endforelse
        </div>
    </div>

</div>
@endsection