<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>56'30 Studio Cafe - Self-Capture Photo Studio</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,600;0,700;1,400&family=DM+Sans:wght@300;400;500;600;700&family=Cormorant+Garamond:ital,wght@0,400;0,600;1,400&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --gold: #C9A96E;
            --gold-light: #D4BA8A;
            --gold-dark: #A8884D;
            --cream: #FAF6F1;
            --cream-dark: #F0E8DD;
            --warm-white: #FFFDF9;
            --charcoal: #2C2C2C;
            --charcoal-light: #4A4A4A;
            --espresso: #1A1A1A;
            --sage: #8B9E8B;
            --sage-light: #B8C9B8;
            --blush: #E8D5CC;
            --shadow-sm: 0 2px 8px rgba(0,0,0,0.06);
            --shadow-md: 0 4px 20px rgba(0,0,0,0.08);
            --shadow-lg: 0 8px 40px rgba(0,0,0,0.12);
            --shadow-xl: 0 20px 60px rgba(0,0,0,0.15);
            --radius-sm: 8px;
            --radius-md: 12px;
            --radius-lg: 20px;
            --radius-xl: 28px;
        }

        body {
            font-family: 'DM Sans', sans-serif;
            background: var(--cream);
            color: var(--charcoal);
            line-height: 1.7;
            -webkit-font-smoothing: antialiased;
            overflow-x: hidden;
        }

        a { text-decoration: none; color: inherit; }
        img { max-width: 100%; }

        /* === NAVBAR === */
        .navbar {
            position: fixed; top: 0; left: 0; right: 0; z-index: 1000;
            padding: 18px 40px;
            background: rgba(255,253,249,0.85);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border-bottom: 1px solid rgba(201,169,110,0.15);
            transition: all 0.4s ease;
        }
        .navbar.scrolled {
            padding: 12px 40px;
            background: rgba(255,253,249,0.95);
            box-shadow: var(--shadow-sm);
        }
        .navbar-inner {
            max-width: 1280px; margin: 0 auto;
            display: flex; align-items: center; justify-content: space-between;
        }
        .navbar-brand {
            display: flex; align-items: center; gap: 12px;
        }
        .navbar-logo {
            width: 42px; height: 42px; border-radius: 50%;
            background: linear-gradient(135deg, var(--gold), var(--gold-dark));
            display: flex; align-items: center; justify-content: center;
            font-family: 'Playfair Display', serif;
            font-size: 16px; font-weight: 700; color: #fff;
            box-shadow: 0 2px 12px rgba(201,169,110,0.3);
        }
        .navbar-text {
            font-family: 'Playfair Display', serif;
            font-size: 20px; font-weight: 600; color: var(--charcoal);
            letter-spacing: -0.3px;
        }
        .navbar-text span { color: var(--gold); }
        .navbar-links { display: flex; gap: 32px; align-items: center; }
        .navbar-links a {
            font-size: 14px; font-weight: 500; color: var(--charcoal-light);
            position: relative; padding: 4px 0; transition: color 0.3s;
            letter-spacing: 0.3px;
        }
        .navbar-links a::after {
            content: ''; position: absolute; bottom: -2px; left: 0;
            width: 0; height: 2px; background: var(--gold);
            transition: width 0.3s ease;
        }
        .navbar-links a:hover { color: var(--gold-dark); }
        .navbar-links a:hover::after { width: 100%; }
        .navbar-links form { display: inline; }

        /* === PAGE HEADER === */
        .page-header {
            padding: 140px 40px 80px;
            text-align: center;
            position: relative; overflow: hidden;
            background: linear-gradient(135deg, var(--espresso) 0%, #3a3028 50%, var(--charcoal) 100%);
        }
        .page-header::before {
            content: ''; position: absolute; inset: 0;
            background: radial-gradient(ellipse at 30% 50%, rgba(201,169,110,0.15), transparent 60%),
                        radial-gradient(ellipse at 70% 80%, rgba(139,158,139,0.1), transparent 50%);
        }
        .page-header h1 {
            font-family: 'Playfair Display', serif;
            font-size: 48px; font-weight: 700; color: #fff;
            margin-bottom: 12px; position: relative;
            letter-spacing: -0.5px;
        }
        .page-header p {
            font-size: 16px; color: rgba(255,255,255,0.7);
            position: relative; max-width: 500px; margin: 0 auto;
            font-weight: 300;
        }
        .page-header .header-accent {
            width: 60px; height: 3px; background: var(--gold);
            margin: 20px auto 0; border-radius: 2px; position: relative;
        }

        /* === CONTAINER === */
        .container { max-width: 1200px; margin: 0 auto; padding: 60px 40px; }

        /* === BUTTONS === */
        .btn {
            display: inline-flex; align-items: center; justify-content: center;
            padding: 12px 28px; border-radius: 100px;
            font-size: 14px; font-weight: 600; cursor: pointer;
            border: none; transition: all 0.3s ease;
            letter-spacing: 0.3px; font-family: 'DM Sans', sans-serif;
        }
        .btn-primary {
            background: linear-gradient(135deg, var(--gold), var(--gold-dark));
            color: #fff;
            box-shadow: 0 4px 16px rgba(201,169,110,0.3);
        }
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 24px rgba(201,169,110,0.4);
        }
        .btn-secondary {
            background: var(--cream-dark); color: var(--charcoal);
        }
        .btn-secondary:hover { background: #e4dcd0; }
        .btn-sm { padding: 8px 20px; font-size: 12px; }
        .btn-outline {
            background: transparent; border: 2px solid var(--gold);
            color: var(--gold);
        }
        .btn-outline:hover {
            background: var(--gold); color: #fff;
        }

        /* === CARDS === */
        .card {
            background: #fff; border-radius: var(--radius-lg);
            overflow: hidden; transition: all 0.4s ease;
            border: 1px solid rgba(201,169,110,0.1);
        }
        .card:hover {
            box-shadow: var(--shadow-lg);
            transform: translateY(-4px);
        }

        /* === BADGES === */
        .badge {
            display: inline-flex; align-items: center; gap: 4px;
            padding: 5px 14px; border-radius: 100px;
            font-size: 11px; font-weight: 600; text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .badge-success { background: #E8F5E9; color: #2E7D32; }
        .badge-warning { background: #FFF8E1; color: #F57F17; }
        .badge-danger { background: #FFEBEE; color: #C62828; }
        .badge-info { background: #E3F2FD; color: #1565C0; }
        .badge-secondary { background: #F5F5F5; color: #757575; }

        /* === FORMS === */
        .form-group { margin-bottom: 22px; }
        .form-group label {
            display: block; font-size: 13px; font-weight: 600;
            margin-bottom: 8px; color: var(--charcoal);
            letter-spacing: 0.3px;
        }
        .form-control {
            width: 100%; padding: 12px 16px;
            border: 2px solid var(--cream-dark);
            border-radius: var(--radius-sm); font-size: 14px;
            font-family: 'DM Sans', sans-serif;
            transition: all 0.3s ease; background: #fff;
            color: var(--charcoal);
        }
        .form-control:focus {
            outline: none; border-color: var(--gold);
            box-shadow: 0 0 0 4px rgba(201,169,110,0.1);
        }
        .form-control::placeholder { color: #bbb; }
        select.form-control {
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 12 12'%3E%3Cpath fill='%23999' d='M6 8L1 3h10z'/%3E%3C/svg%3E");
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
        .alert-success { background: #E8F5E9; color: #2E7D32; border: 1px solid #C8E6C9; }
        .alert-error { background: #FFEBEE; color: #C62828; border: 1px solid #FFCDD2; }
        .alert-warning { background: #FFF8E1; color: #F57F17; border: 1px solid #FFECB3; }

        /* === FOOTER === */
        .footer {
            background: var(--espresso); color: rgba(255,255,255,0.6);
            padding: 60px 40px 40px; text-align: center;
            position: relative;
        }
        .footer::before {
            content: ''; position: absolute; top: 0; left: 0; right: 0;
            height: 1px;
            background: linear-gradient(90deg, transparent, var(--gold), transparent);
        }
        .footer-brand {
            font-family: 'Playfair Display', serif;
            font-size: 24px; font-weight: 600; color: #fff;
            margin-bottom: 12px;
        }
        .footer-brand span { color: var(--gold); }
        .footer p { font-size: 13px; line-height: 1.8; }
        .footer a { color: var(--gold-light); transition: color 0.3s; }
        .footer a:hover { color: var(--gold); }
        .footer-divider {
            width: 40px; height: 1px; background: var(--gold);
            margin: 20px auto; opacity: 0.4;
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
        @keyframes shimmer {
            0% { background-position: -200% 0; }
            100% { background-position: 200% 0; }
        }
        .animate-in { animation: fadeInUp 0.6s ease forwards; }

        /* === RESPONSIVE === */
        @media (max-width: 768px) {
            .navbar { padding: 14px 20px; }
            .navbar.scrolled { padding: 10px 20px; }
            .navbar-links { gap: 16px; }
            .navbar-links a { font-size: 13px; }
            .page-header { padding: 120px 20px 60px; }
            .page-header h1 { font-size: 32px; }
            .container { padding: 40px 20px; }
            .footer { padding: 40px 20px 30px; }
        }

        @yield('styles')
    </style>
</head>
<body>
    <nav class="navbar" id="navbar">
        <div class="navbar-inner">
            <a href="{{ route('home') }}" class="navbar-brand">
                <div class="navbar-logo">56</div>
                <div class="navbar-text">56'30 <span>Studio</span></div>
            </a>
            <div class="navbar-links">
                <a href="{{ route('home') }}">Home</a>
                <a href="{{ route('booking.index') }}">Book Now</a>
                @auth
                    <a href="{{ route('admin.dashboard') }}">Dashboard</a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" style="background:none;border:none;cursor:pointer;font-size:14px;font-weight:500;color:var(--charcoal-light);padding:0;font-family:inherit;letter-spacing:0.3px;">Logout</button>
                    </form>
                @else
                    <a href="{{ route('login') }}" style="background:var(--charcoal);color:#fff;padding:8px 20px;border-radius:100px;">Sign In</a>
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
    </footer>

    <script>
        window.addEventListener('scroll', () => {
            document.getElementById('navbar').classList.toggle('scrolled', window.scrollY > 50);
        });
    </script>
    @yield('scripts')
</body>
</html>
