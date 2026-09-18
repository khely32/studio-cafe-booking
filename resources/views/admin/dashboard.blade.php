@extends('admin.layout')
@section('title', 'Dashboard')

@section('styles')
<style>
.dash-page {
    padding: 28px 32px;
    background: #F7F4F0;
    min-height: calc(100vh - 64px);
}

/* ── Greeting ── */
.dg-head {
    display: flex; align-items: center; justify-content: space-between;
    margin-bottom: 32px; gap: 16px; flex-wrap: wrap;
}
.dg-title { font-size: 28px; font-weight: 800; color: #2D2621; letter-spacing: -0.4px; }
.dg-sub { font-size: 14px; color: #7C7267; margin-top: 4px; }
.dg-sub strong { font-weight: 700; color: #8C6D58; }

/* ── 3-card grid ── */
.dg-grid {
    display: grid; grid-template-columns: repeat(3, 1fr);
    gap: 24px; margin-bottom: 40px;
}
.dg-card {
    background:#FAF8F5; border: 1px solid #E8E2D9; border-radius: 20px;
    padding: 24px; box-shadow: 0 1px 3px rgba(45,38,33,0.05);
    transition: all 0.25s ease;
    display: flex; flex-direction: column;
}
.dg-card:hover { box-shadow: 0 8px 24px rgba(45,38,33,0.08); transform: translateY(-2px); }
.dg-card-hd {
    display: flex; justify-content: space-between; align-items: center;
    margin-bottom: 24px;
}
.dg-card-hd .dg-label {
    font-size: 11px; font-weight: 700; letter-spacing: 0.08em;
    text-transform: uppercase; color: #7C7267;
}
.dg-card-hd .dg-dots { color: #A3988C; cursor: pointer; font-weight: 700; letter-spacing: 1px; }
.dg-card-hd .dg-dots:hover { color: #2D2621; }

/* ── Card 1: My booking page ── */
.dg-bp-center { flex: 1; display: flex; flex-direction: column; align-items: center; text-align: center; }
.dg-logo {
    width: 80px; height: 80px; border-radius: 50%;
    background: #2D2621; color: #fff;
    display: flex; align-items: center; justify-content: center;
    font-size: 17px; font-weight: 900; letter-spacing: 0.3px;
    border: 4px solid #F7F4F0; box-shadow: 0 6px 18px rgba(45,38,33,0.18);
    margin-bottom: 14px;
}
.dg-bp-name { font-size: 18px; font-weight: 700; color: #2D2621; }
.dg-bp-handle { font-size: 12px; color: #7C7267; margin-bottom: 24px; }
.dg-bp-actions { display: flex; gap: 10px; width: 100%; margin-bottom: 24px; }
.dg-bp-btn {
    flex: 1; display: inline-flex; align-items: center; justify-content: center; gap: 6px;
    padding: 9px 12px; border: 1px solid #E8E2D9; border-radius: 12px;
    background:#FAF8F5; color: #2D2621; cursor: pointer;
    font-size: 12px; font-weight: 600; font-family: inherit;
    transition: all 0.2s; text-decoration: none;
}
.dg-bp-btn svg { width: 14px; height: 14px; }
.dg-bp-btn:hover { background: #FAF7F2; border-color: #D9CFC2; }
.dg-bp-link {
    margin-top: auto; font-size: 12px; font-weight: 700; color: #8C6D58;
    text-decoration: none; display: inline-flex; align-items: center; gap: 5px;
    transition: color 0.2s;
}
.dg-bp-link svg { width: 14px; height: 14px; }
.dg-bp-link:hover { color: #3D312A; }

/* ── Card 2: Open bookings ── */
.dg-ub-center { flex: 1; display: flex; align-items: center; justify-content: center; text-align: center; }
.dg-ub-empty { font-size: 14px; font-weight: 500; color: #A3988C; }
.dg-ub-event { font-size: 18px; font-weight: 700; color: #2D2621; line-height: 1.35; margin-bottom: 8px; }
.dg-ub-meta { font-size: 13px; color: #7C7267; margin-bottom: 3px; }
.dg-ub-type { font-size: 13px; font-weight: 600; color: #8C6D58; }

/* ── Card 3: Chrome extension ── */
.dg-card.chrome { background: #FAF7F2; }
.dg-chrome-center { flex: 1; display: flex; flex-direction: column; align-items: center; text-align: center; }
.dg-chrome-icon {
    width: 48px; height: 48px; border-radius: 16px;
    background:#FAF8F5; border: 1px solid #E8E2D9;
    display: flex; align-items: center; justify-content: center;
    box-shadow: 0 2px 8px rgba(45,38,33,0.06); margin-bottom: 16px;
}
.dg-chrome-icon svg { width: 24px; height: 24px; color: #8C6D58; }
.dg-chrome-title { font-size: 16px; font-weight: 700; color: #2D2621; margin-bottom: 8px; }
.dg-chrome-desc { font-size: 12px; color: #7C7267; line-height: 1.6; max-width: 280px; margin-bottom: 20px; }
.dg-chrome-btn {
    margin-top: auto; width: 100%; padding: 12px; border-radius: 12px;
    background: #2D2621; color: #fff; border: none; cursor: pointer;
    font-size: 12px; font-weight: 700; font-family: inherit;
    transition: all 0.2s; box-shadow: 0 4px 12px rgba(45,38,33,0.18); text-decoration: none;
    display: inline-flex; align-items: center; justify-content: center;
}
.dg-chrome-btn:hover { background: #3D312A; box-shadow: 0 6px 18px rgba(45,38,33,0.24); }

/* ── Useful links ── */
.dg-ul-title { font-size: 18px; font-weight: 700; color: #2D2621; margin-bottom: 16px; }
.dg-ul-grid { display: grid; grid-template-columns: repeat(5, 1fr); gap: 16px; }
.dg-ul-item {
    background:#FAF8F5; border: 1px solid #E8E2D9; border-radius: 16px;
    padding: 16px; box-shadow: 0 1px 3px rgba(45,38,33,0.05);
    text-decoration: none; color: inherit;
    transition: all 0.25s ease; display: block;
}
.dg-ul-item:hover { border-color: #8C6D58; transform: translateY(-2px); box-shadow: 0 6px 16px rgba(45,38,33,0.06); }
.dg-ul-icon {
    width: 40px; height: 40px; border-radius: 12px;
    background: #F7F4F0; color: #8C6D58;
    display: flex; align-items: center; justify-content: center; margin-bottom: 12px;
}
.dg-ul-icon svg { width: 18px; height: 18px; }
.dg-ul-item.active { border: 2px solid #2D2621; box-shadow: 0 6px 16px rgba(45,38,33,0.08); }
.dg-ul-item.active .dg-ul-icon { background: #2D2621; color: #fff; }
.dg-ul-item h4 { font-size: 14px; font-weight: 700; color: #2D2621; margin-bottom: 3px; }
.dg-ul-item p { font-size: 12px; color: #7C7267; }

@keyframes fadeUp { from { opacity: 0; transform: translateY(14px); } to { opacity: 1; transform: translateY(0); } }
.anim { animation: fadeUp 0.45s cubic-bezier(0.4,0,0.2,1) both; }
.anim-d1 { animation-delay: 0.05s; }
.anim-d2 { animation-delay: 0.10s; }
.anim-d3 { animation-delay: 0.15s; }
.anim-d4 { animation-delay: 0.20s; }
.anim-d5 { animation-delay: 0.25s; }

@media (max-width: 1100px) {
    .dash-page { padding: 20px 16px; }
    .dg-grid { grid-template-columns: 1fr; }
    .dg-ul-grid { grid-template-columns: repeat(3, 1fr); }
}
@media (max-width: 700px) {
    .dg-ul-grid { grid-template-columns: repeat(2, 1fr); }
    .dg-title { font-size: 22px; }
}
@media (max-width: 480px) {
    .dg-ul-grid { grid-template-columns: 1fr; }
}
</style>
@endsection

@section('content')

<div class="dash-page">

    {{-- Greeting --}}
    <div class="dg-head anim anim-d1">
        <div>
            <h1 class="dg-title">Good evening, {{ $user->name }} 🌙</h1>
            <p class="dg-sub">Welcome to <strong>56'30 Studio Cafe</strong> dashboard.</p>
        </div>
    </div>

    {{-- 3 Top Cards --}}
    <div class="dg-grid">

        {{-- Card 1: My booking page --}}
        <div class="dg-card anim anim-d2">
            <div class="dg-card-hd">
                <span class="dg-label">My booking page</span>
                <span class="dg-dots">•••</span>
            </div>
            <div class="dg-bp-center">
                <div class="dg-logo">56'30</div>
                <h3 class="dg-bp-name">56'30 Studio Cafe</h3>
                <p class="dg-bp-handle">5630studiocafe</p>
                <div class="dg-bp-actions">
                    <button class="dg-bp-btn" onclick="copyLink('{{ url('/') }}')">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="9" y="9" width="13" height="13" rx="2"/><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"/></svg>
                        Copy link
                    </button>
                    <a href="{{ $pages->first() ? route('admin.pages.edit', $pages->first()) : '#' }}" class="dg-bp-btn">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                        Edit
                    </a>
                </div>
                <a href="{{ $pages->first() ? route('pages.public', $pages->first()->slug) : '#' }}" target="_blank" class="dg-bp-link">
                    View booking page
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
                </a>
            </div>
        </div>

        {{-- Card 2: Open bookings --}}
        <div class="dg-card anim anim-d3">
            <div class="dg-card-hd">
                <span class="dg-label">Open bookings</span>
            </div>
            <div class="dg-ub-center">
                @if($upcomingBookings->count() > 0)
                    @php $b = $upcomingBookings->first(); @endphp
                    <div>
                        <div class="dg-ub-event">{{ $b->customer_name }} &amp; 56'30 Studio Cafe</div>
                        <div class="dg-ub-meta">{{ \Carbon\Carbon::parse($b->booking_date)->format('l, F jS, Y') }}</div>
                        <div class="dg-ub-meta">10 mins · {{ \Carbon\Carbon::parse($b->booking_time)->format('g:i A') }}</div>
                        <div class="dg-ub-type">{{ $b->service->name ?? 'Booking' }}</div>
                    </div>
                @else
                    <p class="dg-ub-empty">No open bookings</p>
                @endif
            </div>
            <div style="text-align:center;margin-top:auto;">
                <a href="{{ route('admin.bookings') }}" class="dg-bp-link">
                    View booking details
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
                </a>
            </div>
        </div>

        {{-- Card 3: Chrome extension --}}
        <div class="dg-card chrome anim anim-d4">
            <div class="dg-card-hd">
                <span class="dg-label">Chrome extension</span>
            </div>
            <div class="dg-chrome-center">
                <div class="dg-chrome-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><circle cx="12" cy="12" r="4"/><line x1="21.17" y1="8" x2="12" y2="8"/><line x1="3.95" y1="6.06" x2="8.54" y2="14"/><line x1="10.88" y1="21.94" x2="15.46" y2="14"/></svg>
                </div>
                <h3 class="dg-chrome-title">Chrome extension</h3>
                <p class="dg-chrome-desc">
                    The easy way to share individual times or send links to your booking page without the battle of switching between tabs.
                </p>
                <a href="#" class="dg-chrome-btn">Add to Chrome &mdash; it's free!</a>
            </div>
        </div>

    </div>

    {{-- Useful Links --}}
    <div class="anim anim-d5">
        <h2 class="dg-ul-title">Useful Links</h2>
        <div class="dg-ul-grid">
            {{-- Link 1: Templates --}}
            <a href="{{ route('admin.templates.index') }}" class="dg-ul-item">
                <div class="dg-ul-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/></svg>
                </div>
                <h4>Templates</h4>
                <p>Create &amp; manage templates</p>
            </a>

            {{-- Link 2: Booking Pages --}}
            <a href="{{ route('admin.pages.index') }}" class="dg-ul-item">
                <div class="dg-ul-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"/><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"/></svg>
                </div>
                <h4>Booking Pages</h4>
                <p>Manage your booking pages</p>
            </a>

            {{-- Link 3: Services --}}
            <a href="{{ route('admin.services.index') }}" class="dg-ul-item">
                <div class="dg-ul-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="7" width="20" height="14" rx="2" ry="2"/><path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"/></svg>
                </div>
                <h4>Services</h4>
                <p>Manage services &amp; pricing</p>
            </a>

            {{-- Link 4: Analytics (Active highlight) --}}
            <a href="{{ route('admin.analytics') }}" class="dg-ul-item active">
                <div class="dg-ul-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="20" x2="18" y2="10"/><line x1="12" y1="20" x2="12" y2="4"/><line x1="6" y1="20" x2="6" y2="14"/></svg>
                </div>
                <h4>Analytics</h4>
                <p>Track your performance</p>
            </a>

            {{-- Link 5: Customers --}}
            <a href="{{ route('admin.bookings') }}" class="dg-ul-item">
                <div class="dg-ul-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/></svg>
                </div>
                <h4>Customers</h4>
                <p>View &amp; manage customers</p>
            </a>
        </div>
    </div>

</div>

<script>
function copyLink(url) {
    navigator.clipboard.writeText(url || window.location.origin).then(function() {
        var t = document.createElement('div');
        t.textContent = 'Link copied!';
        t.style.cssText = 'position:fixed;bottom:80px;right:24px;z-index:9999;background:#2D2621;color:#fff;padding:10px 20px;border-radius:12px;font-size:13px;font-weight:500;opacity:0;transform:translateY(10px);transition:all 0.3s ease;pointer-events:none;box-shadow:0 8px 30px rgba(45,38,33,0.18);';
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