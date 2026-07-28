@extends('layouts.app')

@section('styles')
<style>
    /* === HERO === */
    .hero {
        min-height: 100vh; display: flex; align-items: center; justify-content: center;
        position: relative; overflow: hidden;
        background: var(--espresso);
    }
    .hero::before {
        content: ''; position: absolute; inset: 0;
        background:
            radial-gradient(ellipse at 20% 50%, rgba(201,169,110,0.12), transparent 50%),
            radial-gradient(ellipse at 80% 30%, rgba(139,158,139,0.08), transparent 40%),
            radial-gradient(ellipse at 50% 100%, rgba(232,213,204,0.06), transparent 40%);
    }
    .hero::after {
        content: ''; position: absolute; inset: 0;
        background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23C9A96E' fill-opacity='0.03'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
        opacity: 0.5;
    }
    .hero-content {
        text-align: center; position: relative; z-index: 1;
        max-width: 700px; padding: 0 40px;
    }
    .hero-badge {
        display: inline-flex; align-items: center; gap: 8px;
        padding: 8px 20px; border-radius: 100px;
        background: rgba(201,169,110,0.15); border: 1px solid rgba(201,169,110,0.3);
        color: var(--gold-light); font-size: 12px; font-weight: 600;
        letter-spacing: 2px; text-transform: uppercase;
        margin-bottom: 32px; animation: fadeInUp 0.8s ease;
    }
    .hero h1 {
        font-family: 'Playfair Display', serif;
        font-size: 72px; font-weight: 700; color: #fff;
        line-height: 1.1; margin-bottom: 20px;
        letter-spacing: -1px; animation: fadeInUp 0.8s ease 0.1s both;
    }
    .hero h1 em {
        font-style: italic; color: var(--gold);
        font-family: 'Cormorant Garamond', serif;
    }
    .hero p {
        font-size: 18px; color: rgba(255,255,255,0.6);
        line-height: 1.8; margin-bottom: 40px;
        font-weight: 300; animation: fadeInUp 0.8s ease 0.2s both;
    }
    .hero-actions {
        display: flex; gap: 16px; justify-content: center;
        animation: fadeInUp 0.8s ease 0.3s both;
    }
    .hero-scroll {
        position: absolute; bottom: 40px; left: 50%;
        transform: translateX(-50%);
        color: rgba(255,255,255,0.3); font-size: 12px;
        letter-spacing: 2px; text-transform: uppercase;
        animation: fadeIn 1s ease 1s both;
    }
    .hero-scroll::after {
        content: ''; display: block; width: 1px; height: 40px;
        background: linear-gradient(to bottom, var(--gold), transparent);
        margin: 12px auto 0;
    }

    /* === FEATURES === */
    .features-section { padding: 100px 40px; background: var(--warm-white); }
    .features-grid {
        max-width: 1000px; margin: 0 auto;
        display: grid; grid-template-columns: repeat(3, 1fr); gap: 40px;
    }
    .feature-item { text-align: center; padding: 20px; }
    .feature-icon {
        width: 64px; height: 64px; margin: 0 auto 20px;
        border-radius: 50%;
        background: linear-gradient(135deg, var(--cream), var(--cream-dark));
        display: flex; align-items: center; justify-content: center;
        font-size: 28px;
        box-shadow: 0 4px 16px rgba(201,169,110,0.1);
    }
    .feature-item h3 {
        font-family: 'Playfair Display', serif;
        font-size: 18px; font-weight: 600; margin-bottom: 8px;
    }
    .feature-item p { font-size: 14px; color: var(--charcoal-light); line-height: 1.7; }

    /* === PACKAGES === */
    .packages-section { padding: 100px 40px; background: var(--cream); }
    .section-header {
        text-align: center; margin-bottom: 60px;
    }
    .section-header .accent {
        display: inline-block; font-size: 12px; font-weight: 600;
        color: var(--gold); letter-spacing: 3px; text-transform: uppercase;
        margin-bottom: 12px;
    }
    .section-header h2 {
        font-family: 'Playfair Display', serif;
        font-size: 40px; font-weight: 700; margin-bottom: 12px;
        letter-spacing: -0.5px;
    }
    .section-header p { font-size: 16px; color: var(--charcoal-light); max-width: 500px; margin: 0 auto; }

    .packages-grid {
        max-width: 1200px; margin: 0 auto;
        display: grid; grid-template-columns: repeat(auto-fill, minmax(340px, 1fr)); gap: 28px;
    }
    .package-card {
        background: #fff; border-radius: var(--radius-lg);
        overflow: hidden; transition: all 0.4s ease;
        border: 1px solid rgba(201,169,110,0.08);
        cursor: pointer; position: relative;
    }
    .package-card:hover {
        transform: translateY(-6px);
        box-shadow: var(--shadow-xl);
        border-color: rgba(201,169,110,0.2);
    }
    .package-card-image {
        height: 200px; position: relative; overflow: hidden;
        background: linear-gradient(135deg, var(--cream-dark), var(--blush));
    }
    .package-card-image .img-placeholder {
        width: 100%; height: 100%;
        display: flex; align-items: center; justify-content: center;
        font-size: 48px; opacity: 0.4;
    }
    .package-card-image .price-tag {
        position: absolute; bottom: 16px; right: 16px;
        background: var(--espresso); color: var(--gold);
        padding: 8px 18px; border-radius: 100px;
        font-size: 16px; font-weight: 700;
        box-shadow: var(--shadow-md);
    }
    .package-card-body { padding: 28px; }
    .package-card-body h3 {
        font-family: 'Playfair Display', serif;
        font-size: 20px; font-weight: 700; margin-bottom: 10px;
    }
    .package-card-body .description {
        font-size: 13px; color: var(--charcoal-light);
        line-height: 1.7; margin-bottom: 16px;
        white-space: pre-line;
        display: -webkit-box; -webkit-line-clamp: 4;
        -webkit-box-orient: vertical; overflow: hidden;
    }
    .package-meta {
        display: flex; gap: 16px; font-size: 13px; color: var(--charcoal-light);
        padding-top: 16px; border-top: 1px solid var(--cream-dark);
    }
    .package-meta span { display: flex; align-items: center; gap: 6px; }

    /* === POLICY === */
    .policy-section {
        padding: 80px 40px;
        background: var(--warm-white);
    }
    .policy-card {
        max-width: 900px; margin: 0 auto;
        background: #fff; border-radius: var(--radius-xl);
        padding: 48px; position: relative; overflow: hidden;
        border: 1px solid rgba(201,169,110,0.1);
        box-shadow: var(--shadow-md);
    }
    .policy-card::before {
        content: ''; position: absolute; top: 0; left: 0; right: 0;
        height: 4px; background: linear-gradient(90deg, var(--gold), var(--sage), var(--gold));
    }
    .policy-card h3 {
        font-family: 'Playfair Display', serif;
        font-size: 24px; font-weight: 700; margin-bottom: 24px;
    }
    .policy-card .policy-content {
        font-size: 14px; color: var(--charcoal-light); line-height: 2;
        white-space: pre-line;
    }
    .policy-card .policy-content strong { color: var(--charcoal); }

    @media (max-width: 768px) {
        .hero h1 { font-size: 40px; }
        .hero p { font-size: 16px; }
        .hero-actions { flex-direction: column; align-items: center; }
        .features-grid { grid-template-columns: 1fr; gap: 20px; }
        .packages-grid { grid-template-columns: 1fr; }
        .section-header h2 { font-size: 28px; }
        .policy-card { padding: 28px; }
    }
</style>
@endsection

@section('content')
{{-- HERO --}}
<section class="hero">
    <div class="hero-content">
        <div class="hero-badge">Self-Capture Photo Studio</div>
        <h1>Timeless Moments, <em>Captured Beautifully</em></h1>
        <p>Step into 56'30 Studio Cafe — where every frame tells your story. Professional lighting, curated backdrops, and an experience designed just for you.</p>
        <div class="hero-actions">
            <a href="{{ route('booking.index') }}" class="btn btn-primary" style="padding:16px 40px;font-size:15px;">
                Book Your Session
            </a>
            <a href="#packages" class="btn btn-outline" style="padding:16px 40px;font-size:15px;border-color:rgba(255,255,255,0.3);color:rgba(255,255,255,0.8);">
                View Packages
            </a>
        </div>
    </div>
    <div class="hero-scroll">Scroll</div>
</section>

{{-- FEATURES --}}
<section class="features-section">
    <div class="features-grid">
        <div class="feature-item">
            <div class="feature-icon">📸</div>
            <h3>Self-Capture Studio</h3>
            <p>Just you, the camera, and a remote. Capture candid moments at your own pace with our professional equipment.</p>
        </div>
        <div class="feature-item">
            <div class="feature-icon">🎨</div>
            <h3>Curated Backdrops</h3>
            <p>Choose from our hand-painted, themed backdrops — from cozy home setups to tropical vibes.</p>
        </div>
        <div class="feature-item">
            <div class="feature-icon">✨</div>
            <h3>Professional Lighting</h3>
            <p>Studio-grade lighting setups ensure every shot looks magazine-worthy, no filters needed.</p>
        </div>
    </div>
</section>

{{-- PACKAGES --}}
<section class="packages-section" id="packages">
    <div class="section-header">
        <div class="accent">Our Packages</div>
        <h2>Choose Your Experience</h2>
        <p>From solo portraits to group celebrations, find the perfect package for your moment.</p>
    </div>

    <div class="packages-grid">
        @foreach($services as $index => $service)
        <div class="package-card" onclick="window.location='{{ route('booking.index') }}?service={{ $service->id }}'" style="animation: fadeInUp 0.6s ease {{ $index * 0.08 }}s both;">
            <div class="package-card-image" style="background:linear-gradient(135deg, {{ ['#E8D5CC','#D4E4D9','#DCE4ED','#E8E0D4','#D5D8E4','#E4D5D8'][$index % 6] }}, {{ ['#F0E8DD','#E8F0E8','#E8ECF4','#F0E8E0','#E8E4F0','#F0E4E8'][$index % 6] }});">
                <div class="img-placeholder">
                    @if($loop->first) 📷 @elseif($loop->index == 1) 👫 @elseif($loop->index == 2) 👨‍👩‍👧‍👦 @elseif($loop->index == 3) 🎉 @else ✨ @endif
                </div>
                <div class="price-tag">₱{{ number_format($service->price, 2) }}</div>
            </div>
            <div class="package-card-body">
                <h3>{{ $service->name }}</h3>
                <div class="description">{{ $service->description }}</div>
                <div class="package-meta">
                    <span>🕐 {{ $service->duration_label }}</span>
                    <span>👥 Up to {{ $service->max_pax }}</span>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</section>

{{-- POLICY --}}
<section class="policy-section">
    <div class="policy-card">
        <h3>Studio Guidelines & Policies</h3>
        <div class="policy-content"><strong>REMINDERS AND PAYMENT POLICY</strong>

Choose an appointment type.
To confirm your session, kindly settle payment to any of the following:
GCash / PayMaya: Ma. Jaliha Unlayao — 09533651548

You can choose to pay full package price or 50% down payment fee to reserve the time slot.
Add-on fees and remaining balance will be settled right after the session.
Your confirmed slot is non-refundable but can be re-scheduled.
Re-scheduling should be made 1-2 days before the scheduled time slot.

Please settle your payment within the day and send proof of payment via FB page messenger (Required).

<strong>SHOWING UP LATE</strong>

Please be on time. We have a 10-minutes grace period before we start the photoshoot timer.
If you arrive later than 15 minutes, your photoshoot time will be reduced.
No show means cancelled slot (down payment will be forfeited).

<strong>STUDIO GUIDES</strong>

This is a self-capture studio — just you, the camera, and remote.
Photographer is also available by client's request (additional charge on your chosen package).
Receive all soft copies within 1 day through Google Drive, 2-3 days for photographer sessions.
1 backdrop color of your choice. Additional P199 fee per backdrop change.
Free use of all props: sunglasses, headbands, artificial flowers, crowns &amp; sash.
You can bring your own props: balloons, fresh flowers, cake, costumes, etc.
No hair and makeup time before photoshoot. Retouch only (except for clients with HMUA appointment).
You can bring your own hair and makeup artist. Book the studio (P700 per hour) before your photoshoot time slot.
Babies are free of charge (5 months and below).
We are a pet-friendly studio. Pets must be on leash and in diapers before and after the photoshoot.
Food and drinks are not allowed inside the studio. We have a waiting area and cafe where you can eat your snacks.</div>
    </div>
</section>
@endsection
