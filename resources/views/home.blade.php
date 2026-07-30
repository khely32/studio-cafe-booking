@extends('layouts.app')

@section('styles')
<style>
    /* === HERO === */
    .hero {
        min-height: 100vh; display: flex; align-items: center; justify-content: center;
        position: relative; overflow: hidden;
        background: var(--gradient-hero);
    }
    .hero::before {
        content: ''; position: absolute; inset: 0;
        background: rgba(0,0,0,0.25);
    }
    .hero::after {
        content: ''; position: absolute; inset: 0;
        background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23C9A96E' fill-opacity='0.03'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
        opacity: 0.5;
    }
    .hero-content {
        text-align: center; position: relative; z-index: 2;
        max-width: 750px; padding: 0 40px;
    }
    .hero-watermark {
        position: absolute; top: 50%; left: 50%;
        transform: translate(-50%, -50%) rotate(-12deg);
        font-family: 'Playfair Display', serif;
        font-size: 120px; font-weight: 700; white-space: nowrap;
        color: rgba(255,255,255,0.06); z-index: 1;
        pointer-events: none; user-select: none;
        letter-spacing: -2px;
    }
    .hero-badge {
        display: inline-flex; align-items: center; gap: 8px;
        padding: 8px 22px; border-radius: 100px;
        background: rgba(255,255,255,0.2); border: 1px solid rgba(255,255,255,0.35);
        color: #fff; font-size: 12px; font-weight: 600;
        letter-spacing: 2px; text-transform: uppercase;
        margin-bottom: 32px; animation: fadeInUp 0.8s ease;
    }
    .hero h1 {
        font-family: 'Playfair Display', serif;
        font-size: 72px; font-weight: 700; color: #fff;
        line-height: 1.1; margin-bottom: 20px;
        letter-spacing: -1px; animation: fadeInUp 0.8s ease 0.1s both;
        text-shadow: 0 2px 12px rgba(0,0,0,0.3), 0 1px 3px rgba(0,0,0,0.2);
    }
    .hero h1 em {
        font-style: italic;
        background: linear-gradient(135deg, #C9A96E, #E8C9A0, #D4A574);
        -webkit-background-clip: text; -webkit-text-fill-color: transparent;
        background-clip: text;
        font-family: 'Cormorant Garamond', serif;
    }
    .hero p {
        font-size: 20px; color: #fff;
        line-height: 1.8; margin-bottom: 40px;
        font-weight: 400; animation: fadeInUp 0.8s ease 0.2s both;
        text-shadow: 0 2px 12px rgba(0,0,0,0.4), 0 1px 3px rgba(0,0,0,0.3);
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
        background: linear-gradient(to bottom, var(--cafe-light), transparent);
        margin: 12px auto 0;
    }

    /* === FEATURES === */
    .features-section { padding: 100px 40px; background: var(--white); }
    .features-grid {
        max-width: 1000px; margin: 0 auto;
        display: grid; grid-template-columns: repeat(3, 1fr); gap: 40px;
    }
    .feature-item { text-align: center; padding: 20px; }
    .feature-icon {
        width: 72px; height: 72px; margin: 0 auto 20px;
        border-radius: 20px;
        display: flex; align-items: center; justify-content: center;
        font-size: 32px;
        box-shadow: 0 8px 30px rgba(0,0,0,0.08);
    }
    .feature-icon.cafe { background: linear-gradient(135deg, #F0E8DD, #E0D4C4); }
    .feature-icon.amber { background: linear-gradient(135deg, #FDF0E0, #F5E0C4); }
    .feature-icon.warm { background: linear-gradient(135deg, #F5EDE0, #EDE3D3); }
    .feature-item h3 {
        font-family: 'Playfair Display', serif;
        font-size: 18px; font-weight: 600; margin-bottom: 8px;
    }
    .feature-item p { font-size: 14px; color: var(--gray-500); line-height: 1.7; }

    /* === PACKAGES === */
    .packages-section { padding: 100px 40px; background: var(--bg); }
    .section-header { text-align: center; margin-bottom: 60px; }
    .section-header .accent {
        display: inline-block; font-size: 12px; font-weight: 600;
        letter-spacing: 3px; text-transform: uppercase; margin-bottom: 12px;
        background: var(--gradient-1); -webkit-background-clip: text;
        -webkit-text-fill-color: transparent; background-clip: text;
    }
    .section-header h2 {
        font-family: 'Playfair Display', serif;
        font-size: 40px; font-weight: 700; margin-bottom: 12px;
        letter-spacing: -0.5px;
    }
    .section-header p { font-size: 16px; color: var(--gray-500); max-width: 500px; margin: 0 auto; }

    .packages-grid {
        max-width: 1200px; margin: 0 auto;
        display: grid; grid-template-columns: repeat(auto-fill, minmax(340px, 1fr)); gap: 28px;
    }
    .package-card {
        background: #fff; border-radius: var(--radius-lg);
        overflow: hidden; transition: all 0.4s ease;
        border: 1px solid var(--gray-200);
        cursor: pointer; position: relative;
    }
    .package-card:hover {
        transform: translateY(-6px);
        box-shadow: var(--shadow-xl);
    }
    .package-card-image {
        height: 200px; position: relative; overflow: hidden;
    }
    .package-card-image .img-placeholder {
        width: 100%; height: 100%;
        display: flex; align-items: center; justify-content: center;
        font-size: 48px;
    }
    .package-card-image img {
        width: 100%; height: 100%; object-fit: cover;
        transition: transform 0.6s ease;
    }
    .package-card:hover .package-card-image img {
        transform: scale(1.08);
    }
    .package-card-image .price-tag {
        position: absolute; bottom: 16px; right: 16px;
        padding: 8px 18px; border-radius: 100px;
        font-size: 16px; font-weight: 700; color: #fff;
        background: rgba(0,0,0,0.5); backdrop-filter: blur(10px);
        box-shadow: var(--shadow-md);
    }
    .package-card-body { padding: 28px; }
    .package-card-body h3 {
        font-family: 'Playfair Display', serif;
        font-size: 20px; font-weight: 700; margin-bottom: 10px;
    }
    .package-card-body .description {
        font-size: 13px; color: var(--gray-500);
        line-height: 1.7; margin-bottom: 16px;
        white-space: pre-line;
        display: -webkit-box; -webkit-line-clamp: 4;
        -webkit-box-orient: vertical; overflow: hidden;
    }
    .package-meta {
        display: flex; gap: 16px; font-size: 13px; color: var(--gray-500);
        padding-top: 16px; border-top: 1px solid var(--gray-100);
    }
    .package-meta span { display: flex; align-items: center; gap: 6px; }

    /* === POLICY === */
    .policy-section { padding: 80px 40px; background: var(--white); }
    .policy-card {
        max-width: 900px; margin: 0 auto;
        background: #fff; border-radius: var(--radius-xl);
        padding: 48px; position: relative; overflow: hidden;
        border: 1px solid var(--gray-200);
        box-shadow: var(--shadow-md);
    }
    .policy-card::before {
        content: ''; position: absolute; top: 0; left: 0; right: 0;
        height: 4px; background: var(--gradient-1);
    }
    .policy-card h3 {
        font-family: 'Playfair Display', serif;
        font-size: 24px; font-weight: 700; margin-bottom: 24px;
    }
    .policy-card .policy-content {
        font-size: 14px; color: var(--gray-500); line-height: 2;
        white-space: pre-line;
    }
    .policy-card .policy-content strong { color: var(--gray-800); }

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
    <div class="hero-watermark">Timeless Moments</div>
    <div class="hero-content">
        <div class="hero-badge">Self-Capture Photo Studio</div>
        <h1>Timeless Moments, <em>Captured Beautifully</em></h1>
        <p>Step into 56'30 Studio Cafe — where every frame tells your story. Professional lighting, curated backdrops, and an experience designed just for you.</p>
        <div class="hero-actions">
            <a href="#packages" class="btn btn-primary" style="padding:16px 40px;font-size:15px;">
                Choose an Appointment Type
            </a>
            <a href="#policy" class="btn btn-outline" style="padding:16px 40px;font-size:15px;">
                View Policies
            </a>
        </div>
    </div>
    <div class="hero-scroll">Scroll</div>
</section>

{{-- POLICY & GUIDES --}}
<section class="policy-section" id="policy">
    <div class="policy-card">
        <h3>Reminders and Payment Policy</h3>
        <div class="policy-content">
            <strong>Choose an appointment type.</strong>

∙ To confirm your session, kindly settle payment to any of the following:

<strong>GCash / PayMaya</strong><br>
Ma. Jaliha Unlayao<br>
09533651548

You can choose to pay full package price or 50% down payment fee to reserve the time slot.
∙ Add-on fees and remaining balance will be settled right after the session.
∙ Your confirmed slot is non-refundable but can be re-scheduled.
∙ Re-scheduling should be made 1-2 days before the scheduled time slot.

Please settle your payment within the day and send proof of payment thru FB page messenger (Required)

<strong>SHOWING UP LATE</strong>

∙ Please be on time. We have a 10-minutes grace period before we start the photoshoot timer. (Retouch and you can change of outfit)
∙ If you arrive later than 15 minutes, your photoshoot time will be reduced.
∙ No show means cancelled slot. (down payment will be forfeited)

I have read and agree to 56'30 Studio's Policy (Required)

THANK YOU AND SEE YOU!
        </div>
    </div>

    <div class="policy-card" style="margin-top:24px;">
        <h3>Studio Guides</h3>
        <div class="policy-content">
∙ This is a self-capture studio. just you, the camera, and remote.
∙ Photographer is also available by client's request. (additional charge on your chosen package)
∙ Receive all soft copies the within 1 day through Google drive, 2 to 3 days for those who avail with photographer session.
∙ For special packages and add-ons, you will select photos for printing after photoshoot (7 to 15 minutes selection and printing time)
∙ 1 backdrop color of your choice. Additional P199 fee per backdrop change.
∙ Free use of all props at the studio: sunglasses, headbands, artificial flowers, crowns &amp; sash.
∙ You can bring your own props: balloons, fresh flowers, cake, costumes, etc.
∙ No hair and makeup time before photoshoot. Retouch only. (except for client with HMUA appointment)
∙ You can bring your own hair and makeup artist. Book the studio (P700 per hour) before your photoshoot time slot for hair and makeup session.
∙ Babies are free of charge (5 months and below)
∙ We are a pet-friendly studio. Pets must be on leash and in diapers before and after the photoshoot.
∙ Food and drinks are not allowed inside the studio. We have waiting area and cafe where you can eat your snacks.
        </div>
    </div>
</section>

{{-- ADD-ONS --}}
<section class="policy-section" style="padding-top:0;">
    <div class="policy-card">
        <h3>Add-Ons</h3>
        <div class="policy-content">
<strong>Extra person</strong><br>
Adult (7 y/o up) — ₱249<br>
Kids (6 y/o below) — ₱199<br>
Infant 5 months and below — FREE

<strong>Pets</strong><br>
1 Pet — FREE<br>
Extra pet — ₱199

<strong>Extend time</strong><br>
10 minutes — ₱249

<strong>Additional Backdrop</strong> — ₱199<br>
(Beige, tropical green, Chocolate brown, black and White)

<strong>Printed copy</strong><br>
4R — ₱80<br>
5R — ₱110<br>
Photo card 2 pcs — ₱100<br>
Photo strip 2 pcs — ₱100<br>
A4 — ₱150<br>
A4 with frame — ₱380 (black, wood &amp; white)

Number Balloon — ₱50 (2ft cream caramel in color)<br>
Fake Cake — ₱60

<strong>Photographer's Fee</strong><br>
10 minutes — ₱500<br>
20 minutes — ₱700<br>
30 minutes — ₱900<br>
1 hour — ₱1,250

Hair &amp; Make up Artist — ₱1,800
        </div>
    </div>
</section>

{{-- PACKAGES --}}
<section class="packages-section" id="packages">
    <div class="section-header">
        <div class="accent">Our Packages</div>
        <h2>Choose Your Experience</h2>
        <p>Select the appointment type that suits you best.</p>
    </div>

    <div class="packages-grid">
        @php
        $gradients = [
            ['bg' => 'linear-gradient(135deg, #F0E8DD, #E0D4C4)', 'icon' => '📷'],
            ['bg' => 'linear-gradient(135deg, #FDF0E0, #F5E0C4)', 'icon' => '👫'],
            ['bg' => 'linear-gradient(135deg, #F5EDE0, #EDE3D3)', 'icon' => '👨‍👩‍👧‍👦'],
            ['bg' => 'linear-gradient(135deg, #FEF3C7, #FFFBEB)', 'icon' => '🎉'],
            ['bg' => 'linear-gradient(135deg, #D1FAE5, #ECFDF5)', 'icon' => '✨'],
            ['bg' => 'linear-gradient(135deg, #FFE4E6, #FFF1F2)', 'icon' => '💖'],
        ];
        @endphp
        @foreach($services as $index => $service)
        <div class="package-card" onclick="window.location='{{ route('booking.index') }}?service={{ $service->id }}'" style="animation: fadeInUp 0.6s ease {{ $index * 0.08 }}s both;">
            <div class="package-card-image" style="background:{{ $gradients[$index % 6]['bg'] }};">
                @if($service->image)
                <img src="{{ $service->image }}" alt="{{ $service->name }}" loading="lazy">
                @else
                <div class="img-placeholder">{{ $gradients[$index % 6]['icon'] }}</div>
                @endif
                <div class="price-tag">₱{{ number_format($service->price, 2) }}</div>
            </div>
            <div class="package-card-body">
                <h3>{{ $service->name }}</h3>
                <div class="description">{!! nl2br(e($service->description)) !!}</div>
                <div class="package-meta">
                    <span>🕐 {{ $service->duration_label }}</span>
                    <span>👥 Up to {{ $service->max_pax }} pax</span>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</section>
@endsection
