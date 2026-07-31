<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'My Dashboard') - 56'30 Studio Cafe</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@500;600;700;800&family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        :root {
            --primary: #1A120D;
            --primary-light: #3A2E22;
            --accent: #0F766E;
            --accent-light: #D1FAE5;
            --accent-dark: #115E59;
            --cafe: #8B6F47;
            --cafe-light: #C9A96E;
            --coffee-brown: #6F4E37;
            --espresso: #3E2723;
            --warm-gold: #C8A96A;
            --soft-gold: #D4AF37;
            --cream: #FFF9F4;
            --beige: #F7F2EB;
            --wood: #B08968;
            --white: #FFFFFF;
            --bg: #FAF8F5;
            --gray-50: #FAF8F5;
            --gray-100: #F0E8DD;
            --gray-200: #E0D4C4;
            --gray-300: #C4B8A8;
            --gray-400: #9C8E7C;
            --gray-500: #7A6C5C;
            --gray-600: #5C4A3A;
            --gray-700: #4A3C2E;
            --gray-800: #3A2E22;
            --gray-900: #1A120D;
            --success: #22C55E;
            --success-bg: #D1FAE5;
            --warning: #F59E0B;
            --warning-bg: #FEF3C7;
            --danger: #EF4444;
            --danger-bg: #FEE2E2;
            --radius: 16px;
            --radius-sm: 10px;
            --radius-xs: 8px;
            --radius-pill: 9999px;
            --shadow-sm: 0 1px 3px rgba(44,30,20,0.08);
            --shadow: 0 1px 3px rgba(44,30,20,0.08), 0 1px 2px rgba(44,30,20,0.04);
            --shadow-md: 0 4px 16px rgba(44,30,20,0.1);
            --shadow-lg: 0 8px 30px rgba(44,30,20,0.12);
            --shadow-xl: 0 20px 60px rgba(44,30,20,0.15);
            --transition: all 0.2s ease;
            --gradient-warm: linear-gradient(135deg, #F8F6F2 0%, #F3ECE3 35%, #EFE7DC 70%, #FAF9F6 100%);
        }
        body {
            font-family: 'Inter', -apple-system, sans-serif;
            min-height: 100vh;
            background: var(--gradient-warm);
            color: var(--gray-800);
            line-height: 1.5;
            -webkit-font-smoothing: antialiased;
        }
        h1, h2, h3, h4, h5, h6 {
            font-family: 'Poppins', sans-serif;
            font-weight: 700;
        }

        .bg-decor {
            position: fixed; inset: 0; z-index: 0;
            pointer-events: none; overflow: hidden;
        }
        .bg-decor .orb {
            position: absolute; border-radius: 50%; filter: blur(80px);
        }
        .bg-decor .orb-1 { width: 400px; height: 400px; top: -150px; right: -80px; background: radial-gradient(circle, rgba(201,169,110,0.12), transparent 70%); }
        .bg-decor .orb-2 { width: 350px; height: 350px; bottom: -100px; left: -80px; background: radial-gradient(circle, rgba(139,111,71,0.1), transparent 70%); }
        .bg-decor .grain {
            position: fixed; inset: 0; opacity: 0.035;
            background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 512 512' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='n'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.75' numOctaves='6' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23n)'/%3E%3C/svg%3E");
            background-size: 512px 512px;
        }

        .topbar {
            background: rgba(255,255,255,0.75); backdrop-filter: blur(24px);
            border-bottom: 1px solid rgba(224,212,196,0.5);
            padding: 0 32px; height: 64px;
            display: flex; align-items: center; justify-content: space-between;
            position: sticky; top: 0; z-index: 100;
        }
        .topbar-brand { display: flex; align-items: center; gap: 12px; }
        .topbar-brand .logo {
            width: 36px; height: 36px; border-radius: 12px;
            background: linear-gradient(135deg, #8B6F47, #C9A96E); color: #fff;
            display: flex; align-items: center; justify-content: center;
        }
        .topbar-brand .logo svg { width: 20px; height: 20px; }
        .topbar-brand .brand-text { display: flex; flex-direction: column; }
        .topbar-brand .brand-text .name {
            font-family: 'Poppins', sans-serif;
            font-weight: 700; font-size: 15px; color: var(--gray-900); line-height: 1.2;
        }
        .topbar-brand .brand-text .name span {
            background: linear-gradient(135deg, #8B6F47, #C9A96E);
            -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;
        }
        .topbar-brand .brand-text .sub {
            font-size: 9px; color: var(--gray-400);
            letter-spacing: 1.5px; text-transform: uppercase; line-height: 1;
        }
        .topbar-right { display: flex; align-items: center; gap: 16px; }
        .topbar-right .user-name { font-weight: 600; color: var(--gray-700); font-size: 14px; }
        .topbar-right a {
            font-size: 13px; font-weight: 500; color: var(--gray-500);
            text-decoration: none; padding: 6px 14px; border-radius: var(--radius-pill);
            transition: var(--transition);
        }
        .topbar-right a:hover { background: var(--gray-100); color: var(--gray-700); }
        .topbar-right .logout-btn {
            background: none; border: none; cursor: pointer;
            font-size: 13px; font-weight: 500; color: var(--gray-400);
            font-family: inherit; padding: 6px 14px; border-radius: var(--radius-pill);
            transition: var(--transition);
        }
        .topbar-right .logout-btn:hover { background: var(--danger-bg); color: var(--danger); }

        .hnav {
            background: rgba(255,255,255,0.6); backdrop-filter: blur(12px);
            border-bottom: 1px solid rgba(224,212,196,0.5);
            padding: 0 32px; display: flex; gap: 4px; align-items: center;
            position: sticky; top: 64px; z-index: 99;
        }
        .hnav-link {
            padding: 14px 20px; font-size: 13px; font-weight: 500;
            color: var(--gray-500); text-decoration: none;
            border-bottom: 2px solid transparent; transition: var(--transition);
        }
        .hnav-link:hover { color: var(--gray-700); background: var(--gray-50); }
        .hnav-link.active { color: var(--primary); border-bottom-color: var(--accent); font-weight: 600; }

        .main-wrap { position: relative; z-index: 1; }
        .main-content { max-width: 1000px; margin: 0 auto; padding: 32px; }

        .card {
            background: rgba(255,255,255,0.85); backdrop-filter: blur(16px);
            border: 1px solid rgba(224,212,196,0.4);
            border-radius: var(--radius);
            box-shadow: 0 10px 35px rgba(0,0,0,.08);
            transition: var(--transition);
        }
        .card:hover { box-shadow: var(--shadow-lg); transform: translateY(-2px); }
        .card-header {
            padding: 20px 24px;
            border-bottom: 1px solid rgba(240,232,221,0.6);
            display: flex; justify-content: space-between; align-items: center;
        }
        .card-header h2 { font-size: 15px; font-weight: 600; color: var(--gray-900); }
        .card-body { padding: 24px; }

        .page-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; }
        .page-title { font-size: 20px; font-weight: 700; color: var(--gray-900); letter-spacing: -0.3px; }
        .page-subtitle { font-size: 14px; color: var(--gray-500); margin-top: 2px; }

        .btn {
            display: inline-flex; align-items: center; justify-content: center;
            gap: 8px; padding: 10px 20px; border-radius: var(--radius-pill);
            font-size: 14px; font-weight: 500; cursor: pointer; border: none;
            transition: var(--transition); font-family: 'Inter', sans-serif;
            text-decoration: none; line-height: 1;
        }
        .btn:hover { transform: translateY(-1px); }
        .btn-primary { background: var(--primary); color: #fff; }
        .btn-primary:hover { background: var(--primary-light); box-shadow: 0 4px 12px rgba(26,18,13,0.2); }
        .btn-accent { background: var(--accent); color: #fff; }
        .btn-accent:hover { background: var(--accent-dark); box-shadow: 0 4px 12px rgba(15,118,110,0.3); }
        .btn-secondary { background: var(--white); color: var(--gray-700); border: 1px solid var(--gray-300); }
        .btn-secondary:hover { background: var(--gray-50); border-color: var(--gray-400); }
        .btn-ghost { background: transparent; color: var(--gray-500); }
        .btn-ghost:hover { background: var(--gray-100); color: var(--gray-700); }
        .btn-sm { padding: 7px 14px; font-size: 12px; }

        .table { width: 100%; border-collapse: collapse; font-size: 13px; }
        .table thead {
            background: linear-gradient(135deg, var(--gray-50), var(--gray-100));
        }
        .table th {
            padding: 12px 16px; text-align: left; font-size: 11px; font-weight: 600;
            color: var(--gray-600); text-transform: uppercase; letter-spacing: 0.5px;
            border-bottom: 1px solid var(--gray-200);
        }
        .table td { padding: 14px 16px; border-bottom: 1px solid var(--gray-100); vertical-align: middle; }
        .table tbody tr:hover { background: rgba(240,232,221,0.4); }

        .badge {
            display: inline-flex; align-items: center; gap: 5px;
            padding: 4px 12px; border-radius: var(--radius-pill);
            font-size: 11px; font-weight: 600; letter-spacing: 0.2px;
        }
        .badge-success { background: var(--success-bg); color: #065F46; }
        .badge-warning { background: var(--warning-bg); color: #92400E; }
        .badge-danger { background: var(--danger-bg); color: #991B1B; }
        .badge-neutral { background: var(--gray-100); color: var(--gray-600); }

        .stats-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 16px; margin-bottom: 28px; }
        .stat-card {
            background: rgba(255,255,255,0.75); backdrop-filter: blur(20px);
            border: 1px solid rgba(224,212,196,0.35);
            border-radius: var(--radius); padding: 20px;
            transition: var(--transition);
            box-shadow: 0 10px 35px rgba(0,0,0,.06);
        }
        .stat-card:hover { box-shadow: var(--shadow-lg); transform: translateY(-2px); }
        .stat-label { font-size: 13px; color: var(--gray-500); margin-bottom: 4px; font-weight: 500; }
        .stat-value { font-size: 28px; font-weight: 700; color: var(--gray-900); letter-spacing: -0.5px; }

        .alert {
            padding: 12px 18px; border-radius: var(--radius-sm);
            margin-bottom: 20px; font-size: 14px;
            display: flex; align-items: center; gap: 10px; font-weight: 500;
        }
        .alert-success { background: var(--success-bg); color: #065F46; border: 1px solid #A7F3D0; }
        .alert-error { background: var(--danger-bg); color: #991B1B; border: 1px solid #FECACA; }

        .empty-state { padding: 50px 20px; text-align: center; }
        .empty-state .icon { font-size: 40px; margin-bottom: 10px; opacity: 0.3; }
        .empty-state p { color: var(--gray-400); font-size: 13px; }

        .chuquel-badge {
            position: fixed; bottom: 24px; right: 24px; z-index: 9999;
            background: rgba(255,255,255,0.85); backdrop-filter: blur(16px);
            border: 1px solid rgba(224,212,196,0.4); border-radius: 16px; padding: 12px 16px;
            box-shadow: 0 4px 24px rgba(44,30,20,0.12);
            text-align: center; min-width: 120px;
            animation: fadeIn 0.5s ease;
        }
        .chuquel-badge svg { width: 32px; height: 32px; margin: 0 auto 4px; display: block; stroke: var(--cafe); }
        .chuquel-badge .name { font-family: 'Poppins', sans-serif; font-weight: 700; font-size: 13px; color: var(--cafe); letter-spacing: 0.5px; }
        .chuquel-badge .tag { font-size: 8px; color: var(--gray-400); text-transform: uppercase; letter-spacing: 1px; }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }

        @media (max-width: 768px) {
            .topbar { padding: 0 16px; }
            .main-content { padding: 20px 16px; }
            .stats-grid { grid-template-columns: 1fr; }
            .hnav { overflow-x: auto; }
        }
    </style>
    @yield('styles')
</head>
<body>
    <div class="bg-decor">
        <div class="orb orb-1"></div>
        <div class="orb orb-2"></div>
        <div class="grain"></div>
    </div>

    <nav class="topbar">
        <div class="topbar-brand">
            <div class="logo">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="12" r="10"/>
                    <circle cx="12" cy="12" r="4"/>
                    <path d="M12 2v4M12 18v4M2 12h4M18 12h4"/>
                    <path d="M4.93 4.93l2.83 2.83M16.24 16.24l2.83 2.83M4.93 19.07l2.83-2.83M16.24 7.76l2.83-2.83"/>
                </svg>
            </div>
            <div class="brand-text">
                <div class="name">56'30 <span>Studio</span></div>
                <div class="sub">by chuquel</div>
            </div>
        </div>
        <div class="topbar-right">
            <span class="user-name">{{ Auth::user()->name }}</span>
            <a href="{{ route('home') }}">Book Session</a>
            <form method="POST" action="{{ route('logout') }}" style="display:inline;">
                @csrf
                <button type="submit" class="logout-btn">Logout</button>
            </form>
        </div>
    </nav>

    <nav class="hnav">
        <a href="{{ route('client.dashboard') }}" class="hnav-link {{ request()->routeIs('client.dashboard') ? 'active' : '' }}">My Bookings</a>
    </nav>

    <div class="main-wrap">
        <div class="main-content">
            @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            @if(session('error'))
            <div class="alert alert-error">{{ session('error') }}</div>
            @endif
            @yield('content')
        </div>
    </div>

    <div class="chuquel-badge">
        <svg viewBox="0 0 24 24" fill="none" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
            <circle cx="12" cy="12" r="10"/>
            <circle cx="12" cy="12" r="4"/>
            <path d="M12 2v4M12 18v4M2 12h4M18 12h4"/>
            <path d="M4.93 4.93l2.83 2.83M16.24 16.24l2.83 2.83M4.93 19.07l2.83-2.83M16.24 7.76l2.83-2.83"/>
        </svg>
        <div class="name">chuquel</div>
        <div class="tag">studio brand</div>
    </div>
</body>
</html>