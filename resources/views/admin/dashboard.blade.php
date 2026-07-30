@extends('admin.layout')
@section('title', 'Dashboard')

@section('content')
<div style="max-width:900px;">

    <div style="margin-bottom:28px;">
        <div style="font-size:22px;font-weight:700;color:var(--gray-900);">Welcome back, Jai</div>
    </div>

    <div class="card" style="margin-bottom:20px;">
        <div class="card-header">
            <h2>My booking page</h2>
        </div>
        <div class="card-body" style="display:flex;align-items:center;gap:20px;">
            <div style="width:60px;height:60px;border-radius:14px;background:var(--gradient-1);color:#fff;display:flex;align-items:center;justify-content:center;font-weight:700;font-size:18px;flex-shrink:0;">56</div>
            <div style="flex:1;">
                <div style="font-weight:600;font-size:15px;color:var(--gray-900);">56'30 Studio Cafe</div>
                <div style="font-size:13px;color:var(--gray-400);margin-top:2px;">5630studiocafe</div>
            </div>
            @if($pages->count())
            <a href="{{ url('/page/'.$pages->first()->slug) }}" target="_blank" class="btn btn-secondary">View page →</a>
            @endif
        </div>
    </div>

    <div class="card" style="margin-bottom:20px;">
        <div class="card-header">
            <h2>Upcoming bookings</h2>
            <a href="{{ route('admin.bookings') }}" class="btn btn-sm btn-secondary">View all →</a>
        </div>
        @if($upcomingBookings->isEmpty())
        <div class="empty-state"><div class="icon">📅</div><p>No upcoming bookings.</p></div>
        @else
        <div>
            @foreach($upcomingBookings as $b)
            <div style="display:flex;align-items:center;justify-content:space-between;padding:16px 22px;border-bottom:1px solid var(--gray-100);">
                <div style="display:flex;align-items:center;gap:14px;">
                    <div style="width:44px;height:44px;border-radius:12px;background:var(--gradient-3);color:#fff;display:flex;align-items:center;justify-content:center;font-weight:700;font-size:13px;flex-shrink:0;">
                        {{ strtoupper(substr($b->customer_name, 0, 2)) }}
                    </div>
                    <div>
                        <div style="font-weight:600;font-size:14px;color:var(--gray-900);">{{ $b->customer_name }} and 56'30 Studio Cafe</div>
                        <div style="font-size:13px;color:var(--gray-500);margin-top:2px;">
                            {{ $b->booking_date->format('l, F j, Y') }}
                        </div>
                        <div style="font-size:12px;color:var(--gray-400);margin-top:1px;">
                            {{ $b->service->duration ?? 30 }} mins · {{ \Carbon\Carbon::parse($b->booking_time)->format('g:i A') }}
                        </div>
                    </div>
                </div>
                <div style="display:flex;align-items:center;gap:10px;">
                    <span class="page-card-badge badge-{{ $b->status_badge }}">{{ ucfirst($b->status) }}</span>
                    <a href="{{ route('admin.booking.detail', $b) }}" class="btn btn-sm btn-ghost">View details</a>
                </div>
            </div>
            @endforeach
        </div>
        @endif
    </div>

    <div class="card" style="margin-bottom:20px;border:none;background:linear-gradient(135deg,#FDF6EC,#FFF8EE);box-shadow:0 2px 12px rgba(139,111,71,0.08);">
        <div class="card-body" style="display:flex;align-items:center;gap:20px;">
            <div style="width:48px;height:48px;border-radius:12px;background:#fff;display:flex;align-items:center;justify-content:center;font-size:24px;flex-shrink:0;box-shadow:0 2px 8px rgba(0,0,0,0.06);">🧩</div>
            <div style="flex:1;">
                <div style="font-weight:600;font-size:14px;color:var(--gray-900);">Chrome extension</div>
                <div style="font-size:13px;color:var(--gray-500);margin-top:2px;">The easy way to share individual times or send links to your booking page without the battle of switching between tabs.</div>
            </div>
            <a href="https://chrome.google.com/webstore" target="_blank" class="btn btn-secondary" style="white-space:nowrap;">Install →</a>
        </div>
    </div>

    <h3 style="font-size:15px;font-weight:600;color:var(--gray-900);margin-bottom:14px;">Useful Links</h3>
    <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:14px;margin-bottom:20px;">
        <div class="card" style="cursor:pointer;transition:all 0.15s;" onmouseover="this.style.boxShadow='var(--shadow-md)'" onmouseout="this.style.boxShadow='var(--shadow-sm)'">
            <div class="card-body">
                <div style="font-size:24px;margin-bottom:10px;">🎁</div>
                <div style="font-weight:600;font-size:14px;color:var(--gray-900);margin-bottom:4px;">Refer & Earn</div>
                <div style="font-size:12px;color:var(--gray-500);line-height:1.6;">Tell your friends about 56'30 Studio. They get a discount and you get credits on your next renewal.</div>
            </div>
        </div>
        <div class="card" style="cursor:pointer;transition:all 0.15s;" onmouseover="this.style.boxShadow='var(--shadow-md)'" onmouseout="this.style.boxShadow='var(--shadow-sm)'">
            <div class="card-body">
                <div style="font-size:24px;margin-bottom:10px;">💳</div>
                <div style="font-weight:600;font-size:14px;color:var(--gray-900);margin-bottom:4px;">Take payments</div>
                <div style="font-size:12px;color:var(--gray-500);line-height:1.6;">Accept GCash, PayMaya, and card payments. Set up promo codes and packages for your clients.</div>
            </div>
        </div>
        <div class="card" style="cursor:pointer;transition:all 0.15s;" onmouseover="this.style.boxShadow='var(--shadow-md)'" onmouseout="this.style.boxShadow='var(--shadow-sm)'">
            <div class="card-body">
                <div style="font-size:24px;margin-bottom:10px;">⚡</div>
                <div style="font-weight:600;font-size:14px;color:var(--gray-900);margin-bottom:4px;">Automate your tasks</div>
                <div style="font-size:12px;color:var(--gray-500);line-height:1.6;">Sync with your favorite apps to automate booking confirmations, reminders, and follow-ups.</div>
            </div>
        </div>
    </div>

</div>

<div class="chuquel-badge">
    <svg viewBox="0 0 24 24" fill="none" stroke="var(--cafe)" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" style="width:48px;height:48px;margin:0 auto 8px;display:block;">
        <circle cx="12" cy="12" r="10"/>
        <circle cx="12" cy="12" r="4"/>
        <path d="M12 2v4M12 18v4M2 12h4M18 12h4"/>
        <path d="M4.93 4.93l2.83 2.83M16.24 16.24l2.83 2.83M4.93 19.07l2.83-2.83M16.24 7.76l2.83-2.83"/>
    </svg>
    <div class="name">chuquel</div>
    <div class="tag">studio brand</div>
</div>

<style>
.chuquel-badge { display: block; }
</style>
@endsection
