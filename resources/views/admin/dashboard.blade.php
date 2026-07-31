@extends('admin.layout')
@section('title', 'Dashboard')

@section('styles')
<style>
:root {
    --dash-primary: #000000;
    --dash-secondary: #4B5563;
    --dash-text: #111;
    --dash-text-muted: #6B7280;
    --dash-border: rgba(0,0,0,0.06);
    --dash-shadow: 0 1px 3px rgba(0,0,0,0.04), 0 1px 2px rgba(0,0,0,0.03);
    --dash-shadow-hover: 0 4px 16px rgba(0,0,0,0.06);
    --dash-radius: 16px;
    --dash-radius-sm: 12px;
}

.dash-page { padding: 28px 32px; background: #F4F6F8; min-height: calc(100vh - 64px); }

@media (max-width: 1100px) { .dash-page { padding: 20px 16px; } }

.greeting-section { margin-bottom: 28px; }
.greeting-section h1 {
    font-size: 28px; font-weight: 700; color: var(--dash-text);
    letter-spacing: -0.3px;
}

/* ── 3-column grid ── */
.dash-grid {
    display: grid !important;
    grid-template-columns: 1fr 1fr 1fr !important;
    gap: 20px !important;
    margin-bottom: 28px !important;
}

/* ── Glass card base ── */
.g-card {
    background: #fff;
    border: 1px solid #E5E7EB;
    border-radius: var(--dash-radius);
    box-shadow: var(--dash-shadow);
    transition: all 0.25s ease;
    display: flex;
    flex-direction: column;
}
.g-card:hover { box-shadow: var(--dash-shadow-hover); }
.g-card .inner { padding: 24px; flex: 1; display: flex; flex-direction: column; }

.card-heading {
    font-size: 14px; font-weight: 600; color: var(--dash-text);
    display: flex; align-items: center; justify-content: space-between;
    margin-bottom: 20px;
}

/* ── Panel 1: My Booking Page ── */
.bp-center { text-align: center; padding: 4px 0; flex: 1; display: flex; flex-direction: column; align-items: center; justify-content: center; }
.bp-logo-wrap { position: relative; display: inline-block; margin-bottom: 14px; }
.bp-logo {
    width: 72px; height: 72px; border-radius: 50%;
    background: linear-gradient(135deg, #000000, #000000);
    color: #fff; display: flex; align-items: center; justify-content: center;
    font-weight: 700; font-size: 18px; margin: 0 auto;
    box-shadow: 0 4px 16px rgba(0,0,0,0.15);
}
.bp-dot {
    position: absolute; bottom: 2px; right: 2px;
    width: 14px; height: 14px; border-radius: 50%;
    background: #000000; border: 2.5px solid #fff;
}
.bp-title { font-size: 16px; font-weight: 700; color: var(--dash-text); margin-bottom: 2px; }
.bp-handle { font-size: 13px; color: var(--dash-text-muted); margin-bottom: 20px; }

.bp-actions { display: flex; gap: 10px; justify-content: center; }
.bp-actions .bp-pill {
    display: inline-flex; align-items: center; gap: 6px;
    padding: 9px 18px; border-radius: 9999px;
    border: 1px solid #D1D5DB; color: #374151;
    font-size: 13px; font-weight: 500; cursor: pointer;
    background: #fff; font-family: inherit;
    transition: all 0.2s; text-decoration: none;
}
.bp-actions .bp-pill:hover { border-color: var(--dash-primary); color: var(--dash-primary); background: #F9FAFB; }
.bp-actions .bp-pill svg { width: 15px; height: 15px; }

.bp-footer-link {
    text-align: center; padding: 14px 0 0; margin-top: auto;
}
.bp-footer-link a {
    font-size: 13px; font-weight: 500; color: var(--dash-primary);
    text-decoration: none;
}
.bp-footer-link a:hover { text-decoration: underline; }

/* ── Panel 2: Upcoming Bookings ── */
.ub-center { text-align: center; flex: 1; display: flex; flex-direction: column; align-items: center; justify-content: center; padding: 8px 0; }
.ub-center .ub-event { font-size: 18px; font-weight: 700; color: var(--dash-text); margin-bottom: 10px; line-height: 1.3; }
.ub-center .ub-date { font-size: 14px; color: var(--dash-text-muted); margin-bottom: 4px; }
.ub-center .ub-time { font-size: 13px; color: var(--dash-text-muted); margin-bottom: 4px; }
.ub-center .ub-type { font-size: 14px; font-weight: 600; color: var(--dash-primary); }

/* ── Panel 3: Chrome Extension ── */
.chrome-card { background: #F9FAFB !important; }
.chrome-center { text-align: center; flex: 1; display: flex; flex-direction: column; align-items: center; justify-content: center; padding: 4px 0; }
.chrome-icon { margin-bottom: 16px; }
.chrome-icon svg { width: 48px; height: 48px; }
.chrome-title { font-size: 18px; font-weight: 700; color: var(--dash-text); margin-bottom: 10px; }
.chrome-desc { font-size: 13px; color: #6B7280; line-height: 1.6; max-width: 280px; margin: 0 auto 20px; }
.chrome-btn {
    display: inline-flex; align-items: center; gap: 8px;
    padding: 12px 28px; border-radius: 9999px;
    background: #000000; color: #fff;
    font-size: 14px; font-weight: 600; cursor: pointer;
    border: none; font-family: inherit;
    transition: all 0.2s; text-decoration: none;
}
.chrome-btn:hover { background: #1A1A1A; box-shadow: 0 4px 16px rgba(0,0,0,0.2); transform: translateY(-1px); }

/* ── Useful Links ── */
.ul-wrap { margin-bottom: 24px; }
.ul-title {
    font-size: 16px; font-weight: 700; color: var(--dash-text);
    margin-bottom: 16px;
}
.ul-grid {
    display: grid; grid-template-columns: repeat(5, 1fr);
    gap: 14px;
}
.ul-item {
    padding: 20px 16px; border-radius: var(--dash-radius-sm);
    text-decoration: none; color: inherit;
    border: 1px solid #E5E7EB;
    background: #fff;
    transition: all 0.25s; display: block;
    box-shadow: var(--dash-shadow);
}
.ul-item:hover {
    border-color: var(--dash-primary);
    transform: translateY(-3px);
    box-shadow: 0 6px 20px rgba(0,0,0,0.06);
}
.ul-item .ul-icon {
    width: 38px; height: 38px; border-radius: 10px;
    display: flex; align-items: center; justify-content: center;
    font-size: 18px; margin-bottom: 12px;
    background: #F3F4F6; color: var(--dash-primary);
}
.ul-item h4 { font-size: 14px; font-weight: 600; color: var(--dash-text); margin-bottom: 4px; }
.ul-item p { font-size: 12px; color: var(--dash-text-muted); line-height: 1.5; }

@keyframes fadeUp { from { opacity: 0; transform: translateY(16px); } to { opacity: 1; transform: translateY(0); } }
.anim { animation: fadeUp 0.45s cubic-bezier(0.4,0,0.2,1) both; }
.anim-d1 { animation-delay: 0.05s; }
.anim-d2 { animation-delay: 0.10s; }
.anim-d3 { animation-delay: 0.15s; }
.anim-d4 { animation-delay: 0.20s; }
.anim-d5 { animation-delay: 0.25s; }

@media (max-width: 1100px) {
    .dash-grid { grid-template-columns: 1fr 1fr !important; }
    .ul-grid { grid-template-columns: repeat(3, 1fr); }
}
@media (max-width: 860px) {
    .dash-grid { grid-template-columns: 1fr !important; }
    .ul-grid { grid-template-columns: repeat(2, 1fr); }
}
@media (max-width: 500px) {
    .ul-grid { grid-template-columns: 1fr; }
    .greeting-section h1 { font-size: 22px; }
}
</style>
@endsection

@section('content')

<div class="dash-page">

<div class="greeting-section anim anim-d1">
    <h1>Good evening, {{ $user->name }} 🌙</h1>
</div>

{{-- 3 Top Panels --}}
<div class="dash-grid">

    {{-- PANEL 1: My Booking Page --}}
    <div class="g-card anim anim-d3">
        <div class="inner">
            <div class="card-heading">
                <span>My booking page</span>
                <span style="display:flex;align-items:center;gap:2px;color:#9CA3AF;cursor:pointer;">
                    <svg viewBox="0 0 24 24" fill="currentColor" width="18" height="18"><circle cx="12" cy="5" r="1.5"/><circle cx="12" cy="12" r="1.5"/><circle cx="12" cy="19" r="1.5"/></svg>
                </span>
            </div>
            <div class="bp-center">
                <div class="bp-logo-wrap">
                    <div class="bp-logo">56'30</div>
                    <span class="bp-dot"></span>
                </div>
                <div class="bp-title">56'30 Studio Cafe</div>
                <div class="bp-handle">5630studiocafe</div>
                <div class="bp-actions">
                    <button class="bp-pill" onclick="copyLink('{{ url('/') }}')">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71"/><path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71"/></svg>
                        Copy link
                    </button>
                    <a href="{{ $pages->first() ? route('admin.pages.edit', $pages->first()) : '#' }}" class="bp-pill">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                        Edit
                    </a>
                </div>
            </div>
            <div class="bp-footer-link">
                <a href="{{ $pages->first() ? route('pages.public', $pages->first()->slug) : '#' }}" target="_blank">View booking page &rarr;</a>
            </div>
        </div>
    </div>

    {{-- PANEL 2: Open Bookings --}}
    <div class="g-card anim anim-d4">
        <div class="inner">
            <div class="card-heading">
                <span>Open bookings</span>
            </div>
            <div class="ub-center">
                @if($upcomingBookings->count() > 0)
                    @php $b = $upcomingBookings->first(); @endphp
                    <div class="ub-event">{{ $b->customer_name }} &amp; 56'30 Studio Cafe</div>
                    <div class="ub-date">{{ \Carbon\Carbon::parse($b->booking_date)->format('l, F jS, Y') }}</div>
                    <div class="ub-time">10 mins · {{ \Carbon\Carbon::parse($b->booking_time)->format('g:i A') }}</div>
                    <div class="ub-type">{{ $b->service->name ?? 'Booking' }}</div>
                @else
                    <div class="ub-event" style="font-size:15px;color:var(--dash-text-muted);">No open bookings</div>
                @endif
            </div>
            <div class="bp-footer-link">
                <a href="{{ route('admin.bookings') }}">View booking details &rarr;</a>
            </div>
        </div>
    </div>

    {{-- PANEL 3: Chrome Extension --}}
    <div class="g-card chrome-card anim anim-d5">
        <div class="inner">
            <div class="card-heading">
                <span>Chrome extension</span>
            </div>
            <div class="chrome-center">
                <div class="chrome-icon">
                    <svg viewBox="0 0 48 48" fill="none">
                        <circle cx="24" cy="24" r="22" fill="#4285F4"/>
                        <circle cx="24" cy="24" r="10" fill="#fff"/>
                        <path d="M24 2l12 20.8H12L24 2z" fill="#EA4335"/>
                        <path d="M24 46l12-20.8H12L24 46z" fill="#34A853"/>
                        <path d="M2 24l20.8-12v24L2 24z" fill="#FBBC05"/>
                    </svg>
                </div>
                <div class="chrome-title">Chrome extension</div>
                <div class="chrome-desc">The easy way to share individual times or send links to your booking page without the battle of switching between tabs.</div>
                <a href="#" class="chrome-btn">Add to Chrome &mdash; it's free!</a>
            </div>
        </div>
    </div>

</div>

{{-- Useful Links --}}
<div class="ul-wrap anim anim-d5">
    <div class="ul-title">Useful Links</div>
    <div class="ul-grid">
        <a href="{{ route('admin.templates.index') }}" class="ul-item">
            <div class="ul-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="width:18px;height:18px;"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/></svg>
            </div>
            <h4>Templates</h4>
            <p>Create and manage your templates</p>
        </a>
        <a href="{{ route('admin.pages.index') }}" class="ul-item">
            <div class="ul-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="width:18px;height:18px;"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"/><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"/></svg>
            </div>
            <h4>Booking Pages</h4>
            <p>Manage your booking pages</p>
        </a>
        <a href="{{ route('admin.services.index') }}" class="ul-item">
            <div class="ul-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="width:18px;height:18px;"><rect x="2" y="7" width="20" height="14" rx="2" ry="2"/><path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"/></svg>
            </div>
            <h4>Services</h4>
            <p>Manage your services and pricing</p>
        </a>
        <a href="{{ route('admin.analytics') }}" class="ul-item">
            <div class="ul-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="width:18px;height:18px;"><line x1="18" y1="20" x2="18" y2="10"/><line x1="12" y1="20" x2="12" y2="4"/><line x1="6" y1="20" x2="6" y2="14"/></svg>
            </div>
            <h4>Analytics</h4>
            <p>Track your performance</p>
        </a>
        <a href="{{ route('admin.bookings') }}" class="ul-item">
            <div class="ul-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="width:18px;height:18px;"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/></svg>
            </div>
            <h4>Customers</h4>
            <p>View and manage your customers</p>
        </a>
    </div>
</div>

</div>{{-- /.dash-page --}}

<script>
function copyLink(url) {
    navigator.clipboard.writeText(url || window.location.origin).then(function() {
        var t = document.createElement('div');
        t.textContent = 'Link copied!';
        t.style.cssText = 'position:fixed;bottom:80px;right:24px;z-index:9999;background:#000000;color:#fff;padding:10px 20px;border-radius:12px;font-size:13px;font-weight:500;opacity:0;transform:translateY(10px);transition:all 0.3s ease;pointer-events:none;box-shadow:0 8px 30px rgba(0,0,0,0.15);';
        document.body.appendChild(t);
        requestAnimationFrame(function() {
            t.style.opacity = '1';
            t.style.transform = 'translateY(0)';
        });
        setTimeout(function() {
            t.style.opacity = '0';
            t.style.transform = 'translateY(10px)';
            setTimeout(function() { t.remove(); }, 300);
        }, 2000);
    });
}
</script>
@endsection