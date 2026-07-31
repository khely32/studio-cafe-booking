<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>56'30 Studio Cafe - Self-Capture Photo Studio</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --cafe: #8B6F47;
            --cafe-dark: #6B4F35;
            --cafe-light: #C9A96E;
            --coffee-brown: #6F4E37;
            --warm-gold: #C8A96A;
            --espresso: #2C1E14;
            --espresso-dark: #1A120D;
            --cream: #FDF8F0;
            --cream-dark: #F0E4D4;
            --amber: #D4A574;
            --amber-dark: #B8845A;
            --amber-light: #E8C9A0;
            --warm-brown: #5C4A3A;
            --red: #EF4444;
            --green: #0F766E;
            --gradient-1: linear-gradient(135deg, #8B6F47, #C9A96E);
            --gradient-2: linear-gradient(135deg, #6B4F35, #8B6F47);
            --gradient-3: linear-gradient(135deg, #D4A574, #C9A96E);
            --gradient-4: linear-gradient(135deg, #5C4A3A, #8B6F47);
            --gradient-hero: linear-gradient(135deg, #8B6F47 0%, #A07852 25%, #7A6340 50%, #8B6F47 75%, #6B4F35 100%);
            --gradient-warm: linear-gradient(135deg, #F8F6F2 0%, #F3ECE3 35%, #EFE7DC 70%, #FAF9F6 100%);
            --bg-warm-beige: #F7F3EE;
            --bg-cream: #FAF8F5;
            --bg-coffee-latte: #EFE6D8;
            --bg-warm-white: #FCFBF9;
            --bg-light-sage: #EEF4EF;
            --bg: var(--bg-warm-white);
            --white: #FFFFFF;
            --gray-50: #FAF6F1;
            --gray-100: #F0E8DD;
            --gray-200: #E0D4C4;
            --gray-300: #C4B8A8;
            --gray-400: #9C8E7C;
            --gray-500: #7A6C5C;
            --gray-600: #5C4A3A;
            --gray-700: #4A3C2E;
            --gray-800: #3A2E22;
            --gray-900: #1A120D;
            --shadow-sm: 0 1px 3px rgba(44,30,20,0.08);
            --shadow: 0 4px 12px rgba(44,30,20,0.07);
            --shadow-md: 0 4px 16px rgba(44,30,20,0.1);
            --shadow-lg: 0 8px 40px rgba(44,30,20,0.12);
            --shadow-xl: 0 20px 60px rgba(44,30,20,0.15);
            --radius-sm: 10px;
            --radius-md: 14px;
            --radius-lg: 22px;
            --radius-xl: 28px;
            --gold: #C8A96A;
            --gold-dark: #A8894A;
            --gold-light: #E8D4A0;
            --charcoal: #2C1E14;
            --charcoal-light: #5C4A3A;
            --teal: #0F766E;
        }

        body {
            font-family: 'Inter', sans-serif;
            min-height: 100vh;
            background: var(--gradient-warm);
            color: var(--gray-800);
            line-height: 1.7;
            -webkit-font-smoothing: antialiased;
            overflow-x: hidden;
        }

        a { text-decoration: none; color: inherit; }
        img { max-width: 100%; }

        /* ── Background Decor ── */
        .bg-decor {
            position: fixed; inset: 0; z-index: 0;
            pointer-events: none; overflow: hidden;
        }
        .bg-decor .orb {
            position: absolute; border-radius: 50%;
            filter: blur(80px);
        }
        .bg-decor .orb-1 {
            width: 500px; height: 500px; top: -200px; right: -100px;
            background: radial-gradient(circle, rgba(201,169,110,0.12), transparent 70%);
        }
        .bg-decor .orb-2 {
            width: 400px; height: 400px; bottom: -150px; left: -100px;
            background: radial-gradient(circle, rgba(139,111,71,0.1), transparent 70%);
        }
        .bg-decor .orb-3 {
            width: 300px; height: 300px; top: 40%; left: 50%;
            background: radial-gradient(circle, rgba(212,165,116,0.08), transparent 70%);
        }
        .bg-decor .grain {
            position: fixed; inset: 0;
            opacity: 0.025;
            background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 256 256' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='n'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.9' numOctaves='4' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23n)'/%3E%3C/svg%3E");
            background-size: 256px 256px;
        }
        .bg-decor .camera-lens {
            position: absolute; top: 15%; right: 5%;
            width: 180px; height: 180px; border-radius: 50%;
            border: 2px solid rgba(201,169,110,0.06);
            opacity: 0.3;
        }
        .bg-decor .camera-lens::before {
            content: ''; position: absolute; top: 50%; left: 50%;
            transform: translate(-50%, -50%);
            width: 120px; height: 120px; border-radius: 50%;
            border: 1px solid rgba(201,169,110,0.08);
        }
        .bg-decor .camera-lens::after {
            content: ''; position: absolute; top: 50%; left: 50%;
            transform: translate(-50%, -50%);
            width: 60px; height: 60px; border-radius: 50%;
            border: 1px solid rgba(201,169,110,0.05);
        }

        /* ── Section Backgrounds ── */
        .section-warm-beige { background: var(--bg-warm-beige); }
        .section-cream { background: var(--bg-cream); }
        .section-coffee-latte { background: var(--bg-coffee-latte); }
        .section-warm-white { background: var(--bg-warm-white); }
        .section-light-sage { background: var(--bg-light-sage); }
        .section-white { background: var(--white); }

        .section-decor {
            position: relative; z-index: 1;
        }
        .section-decor::before {
            content: ''; position: absolute; inset: 0;
            background: inherit;
            z-index: -1;
        }

        /* ── NAVBAR ── */
        .navbar {
            position: fixed; top: 0; left: 0; right: 0; z-index: 1000;
            padding: 16px 40px;
            background: rgba(253,248,240,0.7);
            backdrop-filter: blur(24px) saturate(180%);
            -webkit-backdrop-filter: blur(24px) saturate(180%);
            border-bottom: 1px solid rgba(139,111,71,0.1);
            transition: all 0.4s ease;
        }
        .navbar.scrolled {
            padding: 10px 40px;
            background: rgba(253,248,240,0.92);
            box-shadow: 0 4px 30px rgba(139,111,71,0.08);
        }
        .navbar-inner {
            max-width: 1280px; margin: 0 auto;
            display: flex; align-items: center; justify-content: space-between;
        }
        .navbar-brand { display: flex; align-items: center; gap: 14px; }
        .navbar-logo {
            width: 54px; height: 54px; border-radius: 16px;
            background: var(--gradient-1);
            display: flex; align-items: center; justify-content: center;
            font-family: 'Poppins', sans-serif;
            font-size: 22px; font-weight: 700; color: #fff;
            box-shadow: 0 0 20px rgba(139,111,71,0.5), 0 0 40px rgba(201,169,110,0.25);
            animation: neonPulse 2s ease-in-out infinite;
        }
        .navbar-brand-col { display: flex; flex-direction: column; }
        .navbar-text {
            font-family: 'Poppins', sans-serif;
            font-size: 26px; font-weight: 700; color: var(--espresso);
            letter-spacing: -0.3px; line-height: 1;
        }
        .navbar-text span { background: var(--gradient-1); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text; }
        .navbar-sub {
            font-size: 11px; font-weight: 600; color: var(--gray-400);
            letter-spacing: 2px; text-transform: uppercase; margin-top: 2px;
        }
        .navbar-links { display: flex; gap: 8px; align-items: center; }
        .navbar-links a {
            font-size: 14px; font-weight: 600; color: var(--cafe);
            padding: 8px 18px; border-radius: 100px; transition: all 0.3s;
            border: 1.5px solid transparent;
        }
        .navbar-links a:hover {
            background: rgba(139,111,71,0.1); color: var(--cafe-dark);
            border-color: rgba(139,111,71,0.4);
            box-shadow: 0 0 12px rgba(139,111,71,0.35), 0 0 30px rgba(139,111,71,0.15);
            text-shadow: 0 0 8px rgba(139,111,71,0.4);
            transform: translateY(-1px);
        }
        .navbar-links form { display: inline; }
        .navbar-links form button {
            font-size: 14px; font-weight: 600; color: var(--cafe);
            padding: 8px 18px; border-radius: 100px; transition: all 0.3s;
            background: transparent; cursor: pointer; font-family: 'Inter', sans-serif;
            border: 1.5px solid transparent;
        }
        .navbar-links form button:hover {
            background: rgba(139,111,71,0.1); color: var(--cafe-dark);
            border-color: rgba(139,111,71,0.4);
            box-shadow: 0 0 12px rgba(139,111,71,0.35), 0 0 30px rgba(139,111,71,0.15);
            text-shadow: 0 0 8px rgba(139,111,71,0.4);
            transform: translateY(-1px);
        }
        .navbar-links .btn-nav-login { display: none; }

        /* === PAGE HEADER === */
        .page-header {
            padding: 140px 40px 80px;
            text-align: center;
            position: relative; overflow: hidden;
            background: var(--gradient-hero);
        }
        .page-header::before {
            content: ''; position: absolute; inset: 0;
            background:
                radial-gradient(ellipse at 20% 50%, rgba(255,255,255,0.12), transparent 50%),
                radial-gradient(ellipse at 80% 30%, rgba(201,169,110,0.2), transparent 50%),
                radial-gradient(ellipse at 50% 80%, rgba(255,255,255,0.08), transparent 50%);
        }
        .page-header::after {
            content: ''; position: absolute; bottom: 0; left: 0; right: 0;
            height: 60px;
            background: linear-gradient(to top, var(--bg-warm-white), transparent);
        }
        .page-header h1 {
            font-family: 'Poppins', sans-serif;
            font-size: 48px; font-weight: 700; color: #fff;
            margin-bottom: 12px; position: relative;
            letter-spacing: -0.5px;
        }
        .page-header p {
            font-size: 16px; color: rgba(255,255,255,0.65);
            position: relative; max-width: 500px; margin: 0 auto; font-weight: 300;
        }
        .page-header .header-accent {
            width: 60px; height: 3px;
            background: var(--gradient-3);
            margin: 20px auto 0; border-radius: 2px; position: relative;
        }

        /* === CONTAINER === */
        .container { max-width: 1200px; margin: 0 auto; padding: 60px 40px; position: relative; z-index: 1; }

        /* === BUTTONS === */
        .btn {
            display: inline-flex; align-items: center; justify-content: center;
            padding: 12px 28px; border-radius: 100px;
            font-size: 14px; font-weight: 600; cursor: pointer;
            border: none; transition: all 0.3s ease;
            letter-spacing: 0.3px; font-family: 'Inter', sans-serif;
            position: relative; overflow: hidden;
        }
        .btn::before {
            content: ''; position: absolute; top: 0; left: -100%; width: 100%; height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
            transition: left 0.5s;
        }
        .btn:hover::before { left: 100%; }
        .btn-primary {
            background: var(--gradient-1); color: #fff;
            box-shadow: 0 0 15px rgba(139,111,71,0.4), 0 0 30px rgba(201,169,110,0.2);
        }
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 0 25px rgba(139,111,71,0.6), 0 0 50px rgba(201,169,110,0.3), 0 0 80px rgba(139,111,71,0.15);
        }
        .btn-secondary {
            background: transparent; color: var(--cafe);
            border: 2px solid var(--cafe-light);
            box-shadow: 0 0 10px rgba(139,111,71,0.12);
        }
        .btn-secondary:hover {
            background: rgba(139,111,71,0.06);
            box-shadow: 0 0 20px rgba(139,111,71,0.25), 0 0 40px rgba(139,111,71,0.1);
            transform: translateY(-1px);
        }
        .btn-sm { padding: 8px 20px; font-size: 12px; }
        .btn-outline {
            background: transparent; border: 2px solid rgba(139,111,71,0.4);
            color: var(--cafe-light);
            box-shadow: 0 0 10px rgba(139,111,71,0.12);
        }
        .btn-outline:hover {
            background: rgba(139,111,71,0.08);
            border-color: var(--cafe);
            box-shadow: 0 0 20px rgba(139,111,71,0.3), 0 0 40px rgba(139,111,71,0.1);
            transform: translateY(-1px);
        }
        .btn-coffee {
            background: var(--coffee-brown); color: #fff;
            box-shadow: 0 4px 16px rgba(111,78,55,0.3);
        }
        .btn-coffee:hover {
            background: var(--cafe-dark);
            transform: translateY(-2px);
            box-shadow: 0 6px 24px rgba(111,78,55,0.4);
        }

        /* === CARDS === */
        .card {
            background: var(--white); border-radius: var(--radius-lg);
            overflow: hidden; transition: all 0.4s ease;
            border: 1px solid var(--gray-200);
            box-shadow: 0 10px 35px rgba(0,0,0,.08);
        }
        .card:hover { box-shadow: var(--shadow-xl); transform: translateY(-4px); }

        .card-warm {
            background: var(--white); border-radius: 18px;
            border: 1px solid var(--gray-200);
            box-shadow: 0 10px 35px rgba(0,0,0,.08);
            transition: all 0.4s ease;
        }
        .card-warm:hover {
            box-shadow: 0 20px 60px rgba(44,30,20,0.15);
            transform: translateY(-4px);
        }

        /* === BADGES === */
        .badge {
            display: inline-flex; align-items: center; gap: 4px;
            padding: 5px 14px; border-radius: 100px;
            font-size: 11px; font-weight: 600; text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .badge-success { background: #D1FAE5; color: #065F46; }
        .badge-warning { background: #FEF3C7; color: #92400E; }
        .badge-danger { background: #FEE2E2; color: #991B1B; }
        .badge-info { background: #DBEAFE; color: #1E40AF; }
        .badge-secondary { background: var(--gray-100); color: var(--gray-600); }

        /* === FORMS === */
        .form-group { margin-bottom: 22px; }
        .form-group label {
            display: block; font-size: 13px; font-weight: 600;
            margin-bottom: 8px; color: var(--gray-700);
        }
        .form-control {
            width: 100%; padding: 12px 16px;
            border: 2px solid var(--gray-200);
            border-radius: var(--radius-sm); font-size: 14px;
            font-family: 'Inter', sans-serif;
            transition: all 0.3s ease; background: #fff;
            color: var(--gray-800);
        }
        .form-control:focus {
            outline: none; border-color: var(--cafe);
            box-shadow: 0 0 0 4px rgba(139,111,71,0.1);
        }
        .form-control::placeholder { color: var(--gray-400); }
        select.form-control {
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 12 12'%3E%3Cpath fill='%2394A3B8' d='M6 8L1 3h10z'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 14px center;
            padding-right: 40px;
        }
        textarea.form-control { resize: vertical; min-height: 90px; }

        /* === ALERTS === */
        .alert {
            padding: 14px 18px; border-radius: var(--radius-sm);
            margin-bottom: 20px; font-size: 14px;
            display: flex; align-items: center; gap: 10px;
        }
        .alert-success { background: #D1FAE5; color: #065F46; border: 1px solid #A7F3D0; }
        .alert-error { background: #FEE2E2; color: #991B1B; border: 1px solid #FECACA; }
        .alert-warning { background: #FEF3C7; color: #92400E; border: 1px solid #FDE68A; }

        /* === FOOTER === */
        .footer {
            background: var(--espresso); color: rgba(255,255,255,0.5);
            padding: 60px 40px 40px; text-align: center;
            position: relative;
        }
        .footer::before {
            content: ''; position: absolute; top: 0; left: 0; right: 0;
            height: 3px;
            background: var(--gradient-1);
        }
        .footer-brand {
            font-family: 'Poppins', sans-serif;
            font-size: 32px; font-weight: 700; color: #fff;
            margin-bottom: 12px;
            text-shadow: 0 0 20px rgba(201,169,110,0.4);
        }
        .footer-brand span {
            background: var(--gradient-1); -webkit-background-clip: text;
            -webkit-text-fill-color: transparent; background-clip: text;
        }
        .footer p { font-size: 13px; line-height: 1.8; }
        .footer a { color: var(--cafe-light); transition: color 0.3s; }
        .footer a:hover { color: #fff; }
        .footer-divider {
            width: 40px; height: 2px; margin: 20px auto; opacity: 0.6;
            background: var(--gradient-1); border-radius: 1px;
        }

        /* === DECORATIVE SECTION DIVIDER === */
        .section-divider {
            height: 1px; background: linear-gradient(to right, transparent, var(--gray-200), transparent);
            margin: 0; border: none;
        }
        .section-divider-wave {
            position: relative; height: 40px; overflow: hidden;
        }
        .section-divider-wave svg {
            position: absolute; bottom: 0; left: 0; width: 100%; height: 40px;
        }

        /* === ANIMATIONS === */
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        .animate-in { animation: fadeInUp 0.6s ease forwards; }

        /* === COFFEE DECOR ELEMENTS === */
        .coffee-ring {
            position: absolute; border-radius: 50%;
            border: 1px solid rgba(200,169,106,0.08);
            pointer-events: none;
        }
        .coffee-ring-1 { width: 80px; height: 80px; top: 10%; right: 8%; }
        .coffee-ring-2 { width: 120px; height: 120px; bottom: 15%; left: 5%; }
        .coffee-ring-3 { width: 60px; height: 60px; top: 30%; left: 10%; }

        .decor-leaf {
            position: absolute; font-size: 40px; opacity: 0.04;
            pointer-events: none; font-family: 'Poppins', sans-serif;
            transform: rotate(-15deg);
        }

        /* === RESPONSIVE === */
        @media (max-width: 768px) {
            .navbar { padding: 14px 20px; }
            .navbar.scrolled { padding: 10px 20px; }
            .navbar-links { gap: 4px; }
            .navbar-links a { font-size: 13px; padding: 6px 12px; }
            .page-header { padding: 120px 20px 60px; }
            .page-header h1 { font-size: 32px; }
            .container { padding: 40px 20px; }
            .footer { padding: 40px 20px 30px; }
            .bg-decor .camera-lens { display: none; }
        }

        @yield('styles')
    </style>
</head>
<body>
    <div class="bg-decor">
        <div class="orb orb-1"></div>
        <div class="orb orb-2"></div>
        <div class="orb orb-3"></div>
        <div class="camera-lens"></div>
        <div class="grain"></div>
    </div>

    <nav class="navbar" id="navbar">
        <div class="navbar-inner">
            <a href="{{ route('home') }}" class="navbar-brand">
                <div class="navbar-logo">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" style="width:26px;height:26px;">
                        <circle cx="12" cy="12" r="10"/>
                        <circle cx="12" cy="12" r="4"/>
                        <path d="M12 2v4M12 18v4M2 12h4M18 12h4"/>
                        <path d="M4.93 4.93l2.83 2.83M16.24 16.24l2.83 2.83M4.93 19.07l2.83-2.83M16.24 7.76l2.83-2.83"/>
                    </svg>
                </div>
                <div class="navbar-brand-col">
                    <div class="navbar-text">56'30 <span>Studio</span></div>
                    <div class="navbar-sub">by chuquel</div>
                </div>
            </a>
            <div class="navbar-links">
                <a href="{{ route('home') }}">Home</a>
                <a href="{{ route('booking.index') }}">Book Now</a>
                @auth
                    <a href="{{ Auth::user()->role === 'admin' ? route('admin.bookings') : route('client.dashboard') }}">Dashboard</a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit">Logout</button>
                    </form>
                @else
                    <a href="{{ route('login') }}">Sign In</a>
                @endauth
            </div>
        </div>
    </nav>

    @yield('content')

    <footer class="footer">
        <div class="footer-brand">56'30 <span>Studio</span></div>
        <p>Self-Capture Photo Studio &amp; Cafe</p>
        <div class="footer-divider"></div>
        <p>GCash / PayMaya: Ma. Jaliha Unlayao &middot; 09533651548</p>
        <p style="margin-top:8px;font-size:12px;opacity:0.5;">&copy; {{ date('Y') }} 56'30 Studio Cafe. All rights reserved.</p>
        <p style="margin-top:4px;font-size:11px;opacity:0.35;letter-spacing:1px;">by chuquel</p>
    </footer>

    <div class="chuquel-badge">
        <svg viewBox="0 0 24 24" fill="none" stroke="var(--cafe)" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
            <circle cx="12" cy="12" r="10"/>
            <circle cx="12" cy="12" r="4"/>
            <path d="M12 2v4M12 18v4M2 12h4M18 12h4"/>
            <path d="M4.93 4.93l2.83 2.83M16.24 16.24l2.83 2.83M4.93 19.07l2.83-2.83M16.24 7.76l2.83-2.83"/>
        </svg>
        <div class="chuquel-name">chuquel</div>
        <div class="chuquel-tag">studio brand</div>
    </div>

    <style>
    .chuquel-badge {
        position: fixed; bottom: 24px; right: 24px; z-index: 9999;
        background: rgba(255,255,255,0.9); backdrop-filter: blur(12px);
        border: 1px solid var(--gray-200);
        border-radius: 16px; padding: 14px 18px;
        box-shadow: 0 4px 24px rgba(44,30,20,0.12);
        text-align: center; min-width: 140px;
        animation: fadeIn 0.5s ease;
    }
    .chuquel-badge svg { width: 40px; height: 40px; margin: 0 auto 6px; display: block; }
    .chuquel-name { font-weight: 700; font-size: 15px; color: var(--cafe); font-family: 'Poppins', sans-serif; letter-spacing: 1px; }
    .chuquel-tag { font-size: 9px; color: var(--gray-400); margin-top: 1px; text-transform: uppercase; letter-spacing: 1px; }
    @keyframes neonPulse { 0%,100%{box-shadow:0 0 20px rgba(139,111,71,0.5),0 0 40px rgba(201,169,110,0.25)} 50%{box-shadow:0 0 30px rgba(139,111,71,0.7),0 0 60px rgba(201,169,110,0.35)} }
    @keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
    @media (max-width: 768px) {
        .chuquel-badge { display: none; }
    }
    </style>

    <script>
        window.addEventListener('scroll', () => {
            document.getElementById('navbar').classList.toggle('scrolled', window.scrollY > 50);
        });
    </script>
    @yield('scripts')
</body>
</html>
