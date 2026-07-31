@extends('admin.layout')
@section('title', 'Analytics')

@section('styles')
<style>
.an-page {
    padding: 28px 32px;
    background: #F8F9FA;
    min-height: calc(100vh - 64px);
}

/* ── Header ── */
.an-header {
    display: flex; align-items: center; justify-content: space-between;
    margin-bottom: 24px; flex-wrap: wrap; gap: 12px;
}
.an-header-left { display: flex; align-items: center; gap: 10px; }
.an-header-left h1 { font-size: 24px; font-weight: 700; color: #111; }
.an-beta {
    display: inline-flex; align-items: center;
    padding: 3px 10px; border-radius: 9999px;
    background: #DBEAFE; color: #1E40AF;
    font-size: 11px; font-weight: 700; letter-spacing: 0.3px;
}
.an-date { font-size: 13px; color: #9CA3AF; }

.an-time-group {
    display: flex; align-items: center;
    border: 1px solid #D1D5DB; border-radius: 100px;
    background: #fff; overflow: hidden;
}
.an-time-group .tg-btn {
    padding: 8px 18px; font-size: 13px; font-weight: 500;
    background: transparent; border: none; color: #6B7280;
    cursor: pointer; font-family: inherit;
    transition: all 0.15s; white-space: nowrap;
    position: relative;
}
.an-time-group .tg-btn:not(:last-child) { border-right: 1px solid #E5E7EB; }
.an-time-group .tg-btn:hover { color: #111; background: #F9FAFB; }
.an-time-group .tg-btn.active { color: #000; font-weight: 600; }
.an-time-group .tg-btn.active::after {
    content: ''; position: absolute; bottom: -1px; left: 50%; transform: translateX(-50%);
    width: 16px; height: 2px; border-radius: 1px; background: #1A3B32;
}

/* ── Banner ── */
.an-banner {
    background: #fff; border: 1px solid #E5E7EB;
    border-radius: 16px; padding: 16px 24px;
    text-align: center; margin-bottom: 24px;
}
.an-banner p { font-size: 15px; color: #374151; }
.an-banner p strong { font-weight: 700; color: #111; }
.an-banner p .muted { color: #6B7280; font-weight: 400; }

/* ── 3-card grid ── */
.an-grid {
    display: grid; grid-template-columns: 1fr 1.8fr 1.2fr;
    gap: 16px; margin-bottom: 16px;
}

.an-card {
    background: #fff; border: 1px solid #E5E7EB;
    border-radius: 16px; display: flex; flex-direction: column;
}
.an-card-hd {
    display: flex; align-items: center; justify-content: center;
    padding: 14px 18px; border-bottom: 1px solid #F3F4F6;
    font-size: 14px; font-weight: 600; color: #111;
    position: relative;
}
.an-card-hd .hd-left { position: absolute; left: 18px; }
.an-card-hd .hd-right { position: absolute; right: 18px; cursor: pointer; color: #9CA3AF; }
.an-card-hd .hd-right svg { width: 16px; height: 16px; }
.an-card-bd {
    flex: 1; padding: 24px 18px;
    display: flex; flex-direction: column; align-items: center; justify-content: center;
}

/* Card 1: Meetings booked */
.mb-icon {
    width: 44px; height: 44px; border-radius: 12px;
    background: #E6F4EA; color: #1A3B32;
    display: flex; align-items: center; justify-content: center;
    margin-bottom: 12px;
}
.mb-icon svg { width: 22px; height: 22px; }
.mb-stat { font-size: 42px; font-weight: 800; color: #111; letter-spacing: -1px; line-height: 1; }
.mb-label { font-size: 13px; color: #6B7280; margin-top: 4px; }

/* Card 2: Insights chart */
.an-card-hd.centered { justify-content: center; }
.insights-chart-wrap {
    padding: 18px 18px 12px; width: 100%; flex: 1;
    display: flex; flex-direction: column;
}
.chart-area {
    display: flex; gap: 8px; flex: 1;
    position: relative;
}
.chart-y {
    display: flex; flex-direction: column; justify-content: space-between;
    padding-right: 6px; flex-shrink: 0;
}
.chart-y span { font-size: 10px; color: #9CA3AF; line-height: 1; text-align: right; min-width: 14px; }
.chart-grid {
    flex: 1; display: flex; flex-direction: column; justify-content: space-between;
    position: relative;
}
.chart-grid .gl {
    border-bottom: 1px solid #F3F4F6; height: 0;
}
.chart-bars {
    position: absolute; inset: 0;
    display: flex; align-items: flex-end; justify-content: space-around;
    padding: 0 4px;
}
.chart-bar-col {
    display: flex; flex-direction: column; align-items: center; gap: 4px;
    flex: 1;
}
.chart-bar-col .bar-pct {
    font-size: 10px; font-weight: 600; color: #6B7280; white-space: nowrap;
}
.chart-bar-col .bar {
    width: 28px; border-radius: 6px 6px 0 0; min-height: 4px;
    transition: height 0.4s ease;
}
.chart-bar-col .bar.dark { background: #1A3B32; }
.chart-bar-col .bar.light { background: #A7D4C0; }
.chart-bar-col .bar-label {
    font-size: 10px; color: #9CA3AF; text-align: center; margin-top: 2px;
}

/* Card 3: Popular pages */
.pp-list { width: 100%; }
.pp-item {
    display: flex; align-items: center; gap: 10px;
    padding: 12px 0; width: 100%;
}
.pp-item:not(:last-child) { border-bottom: 1px solid #F3F4F6; }
.pp-icon { font-size: 18px; flex-shrink: 0; width: 24px; text-align: center; }
.pp-avatar {
    width: 36px; height: 36px; border-radius: 50%;
    background: #1A3B32; color: #fff;
    display: flex; align-items: center; justify-content: center;
    font-size: 10px; font-weight: 700; flex-shrink: 0;
    position: relative;
}
.pp-avatar .pp-dot {
    position: absolute; bottom: 0; right: 0;
    width: 10px; height: 10px; border-radius: 50%;
    background: #22C55E; border: 2px solid #fff;
}
.pp-info { flex: 1; min-width: 0; }
.pp-info .pp-name { font-size: 13px; font-weight: 600; color: #111; }
.pp-info .pp-handle { font-size: 11px; color: #9CA3AF; }
.pp-count {
    display: inline-flex; align-items: center; justify-content: center;
    min-width: 28px; height: 28px; border-radius: 8px;
    background: #F3F4F6; color: #111;
    font-size: 13px; font-weight: 700; padding: 0 8px;
    flex-shrink: 0;
}

/* ── Lower row ── */
.an-grid-2 {
    display: grid; grid-template-columns: 1fr 1fr;
    gap: 16px;
}

.an-card-bd.left { align-items: flex-start; }
.an-card-bd .sms-icon {
    display: flex; align-items: center; gap: 10px; margin-bottom: 8px;
}
.an-card-bd .sms-icon svg { width: 20px; height: 20px; color: #1A3B32; }
.an-card-bd .sms-stat { font-size: 32px; font-weight: 800; color: #111; letter-spacing: -1px; }
.an-card-bd .sms-sub { font-size: 12px; color: #6B7280; }

.amt-row {
    display: flex; align-items: baseline; gap: 6px;
}
.amt-row .amt-stat { font-size: 32px; font-weight: 800; color: #111; letter-spacing: -1px; }
.amt-row .amt-sub { font-size: 13px; color: #6B7280; }

@keyframes fadeUp { from { opacity: 0; transform: translateY(12px); } to { opacity: 1; transform: translateY(0); } }
.anim { animation: fadeUp 0.4s cubic-bezier(0.4,0,0.2,1) both; }
.anim-d1 { animation-delay: 0.05s; }
.anim-d2 { animation-delay: 0.10s; }
.anim-d3 { animation-delay: 0.15s; }
.anim-d4 { animation-delay: 0.20s; }

@media (max-width: 1100px) {
    .an-page { padding: 20px 16px; }
    .an-grid { grid-template-columns: 1fr 1fr; }
    .an-grid > :last-child { grid-column: span 2; }
}
@media (max-width: 700px) {
    .an-grid { grid-template-columns: 1fr; }
    .an-grid > :last-child { grid-column: span 1; }
    .an-grid-2 { grid-template-columns: 1fr; }
    .an-header { flex-direction: column; align-items: flex-start; }
}
</style>
@endsection

@section('content')
<div class="an-page">

    {{-- Header --}}
    <div class="an-header anim anim-d1">
        <div class="an-header-left">
            <h1>Analytics</h1>
            <span class="an-beta">Beta</span>
            <span class="an-date">{{\Carbon\Carbon::today()->format('M d, Y')}}</span>
        </div>
        <div class="an-time-group">
            <button class="tg-btn active">Yesterday</button>
            <button class="tg-btn">Last 7 days</button>
            <button class="tg-btn">Last 30 days</button>
        </div>
    </div>

    {{-- Banner --}}
    <div class="an-banner anim anim-d2">
        <p>🎉 <strong>You're on a roll Ma Jaliha Unlayao!</strong> <span class="muted">You saved 1 hours, and avoided 15 emails yesterday</span></p>
    </div>

    {{-- 3-card grid --}}
    <div class="an-grid anim anim-d3">

        {{-- Card 1: Meetings booked --}}
        <div class="an-card">
            <div class="an-card-hd">
                <span class="hd-left"></span>
                Meetings booked
                <span class="hd-right">
                    <svg viewBox="0 0 24 24" fill="currentColor"><circle cx="12" cy="5" r="1.5"/><circle cx="12" cy="12" r="1.5"/><circle cx="12" cy="19" r="1.5"/></svg>
                </span>
            </div>
            <div class="an-card-bd">
                <div class="mb-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                </div>
                <div class="mb-stat">{{ $overview['today_bookings'] ?? 0 }}</div>
                <div class="mb-label">Yesterday</div>
            </div>
        </div>

        {{-- Card 2: Insights --}}
        @php
            $totalAll = array_sum($statusBreakdown ?? []);
            $cancelled = $statusBreakdown['cancelled'] ?? 0;
            $rescheduled = 0; // not in breakdown, show 0
            $noShow = $statusBreakdown['no_show'] ?? 0;
            $cancelledPct = $totalAll > 0 ? round(($cancelled / $totalAll) * 100) : 0;
            $rescheduledPct = 0;
            $noShowPct = $totalAll > 0 ? round(($noShow / $totalAll) * 100) : 0;
            $maxPct = max($cancelledPct, $rescheduledPct, $noShowPct, 1);
        @endphp
        <div class="an-card">
            <div class="an-card-hd centered">Insights</div>
            <div class="insights-chart-wrap">
                <div class="chart-area">
                    <div class="chart-y">
                        <span>4</span><span>3</span><span>2</span><span>1</span><span>0</span>
                    </div>
                    <div class="chart-grid">
                        <div class="gl"></div><div class="gl"></div><div class="gl"></div><div class="gl"></div><div class="gl" style="border-color:transparent;"></div>
                        <div class="chart-bars">
                            <div class="chart-bar-col">
                                <span class="bar-pct">{{ $cancelledPct }}% / {{ $cancelled }}</span>
                                <div class="bar dark" style="height: {{ max(4, ($cancelledPct / max($maxPct,4)) * 100) }}px;"></div>
                                <span class="bar-label">Cancelled</span>
                            </div>
                            <div class="chart-bar-col">
                                <span class="bar-pct">{{ $rescheduledPct }}% / {{ $rescheduled }}</span>
                                <div class="bar dark" style="height: {{ max(4, ($rescheduledPct / max($maxPct,4)) * 100) }}px;"></div>
                                <span class="bar-label">Rescheduled</span>
                            </div>
                            <div class="chart-bar-col">
                                <span class="bar-pct">{{ $noShowPct }}% / {{ $noShow }}</span>
                                <div class="bar light" style="height: {{ max(4, ($noShowPct / max($maxPct,4)) * 100) }}px;"></div>
                                <span class="bar-label">No show</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Card 3: Popular pages --}}
        <div class="an-card">
            <div class="an-card-hd centered">Popular pages</div>
            <div class="an-card-bd" style="padding:12px 18px 18px;">
                <div class="pp-list">
                    @forelse(($packageBreakdown ?? collect())->take(3) as $pkg)
                    <div class="pp-item">
                        <span class="pp-icon">🏆</span>
                        <div class="pp-avatar">
                            {{ substr($pkg->name ?? 'S', 0, 2) }}
                            <span class="pp-dot"></span>
                        </div>
                        <div class="pp-info">
                            <div class="pp-name">{{ $pkg->name ?? 'Studio Cafe' }}</div>
                            <div class="pp-handle">{{ strtolower(str_replace(' ','', $pkg->name ?? 'studio')) }}cafe</div>
                        </div>
                        <span class="pp-count">{{ $pkg->count ?? 0 }}</span>
                    </div>
                    @empty
                    <div class="pp-item">
                        <span class="pp-icon">🏆</span>
                        <div class="pp-avatar">
                            56
                            <span class="pp-dot"></span>
                        </div>
                        <div class="pp-info">
                            <div class="pp-name">56'30 Studio Cafe</div>
                            <div class="pp-handle">5630studiocafe</div>
                        </div>
                        <span class="pp-count">{{ $overview['today_bookings'] ?? 0 }}</span>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>

    </div>

    {{-- Lower row --}}
    <div class="an-grid-2 anim anim-d4">

        <div class="an-card">
            <div class="an-card-hd">
                SMS sent
                <span class="hd-right">
                    <svg viewBox="0 0 24 24" fill="currentColor"><circle cx="12" cy="5" r="1.5"/><circle cx="12" cy="12" r="1.5"/><circle cx="12" cy="19" r="1.5"/></svg>
                </span>
            </div>
            <div class="an-card-bd left" style="padding:20px 24px;">
                <div class="sms-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
                    <span class="sms-stat">0</span>
                </div>
                <span class="sms-sub">SMS sent yesterday</span>
            </div>
        </div>

        <div class="an-card">
            <div class="an-card-hd">
                Amount received
                <span class="hd-right">
                    <svg viewBox="0 0 24 24" fill="currentColor"><circle cx="12" cy="5" r="1.5"/><circle cx="12" cy="12" r="1.5"/><circle cx="12" cy="19" r="1.5"/></svg>
                </span>
            </div>
            <div class="an-card-bd left" style="padding:20px 24px;">
                <div class="amt-row">
                    <span class="amt-stat">${{ number_format($overview['total_revenue'] ?? 0, 0) }}</span>
                    <span class="amt-sub">total received</span>
                </div>
            </div>
        </div>

    </div>

</div>
@endsection