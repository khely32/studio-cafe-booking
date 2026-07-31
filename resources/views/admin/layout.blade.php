<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin') - 56'30 Studio Cafe</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        :root {
            --primary: #111827;
            --primary-light: #1F2937;
            --accent: #0F766E;
            --accent-light: #CCFBF1;
            --accent-dark: #115E59;
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
            --success-bg: #ECFDF5;
            --warning: #F59E0B;
            --warning-bg: #FFFBEB;
            --danger: #EF4444;
            --danger-bg: #FEF2F2;
            --info: #3B82F6;
            --info-bg: #EFF6FF;
            --radius: 16px;
            --radius-sm: 10px;
            --radius-xs: 8px;
            --radius-pill: 9999px;
            --shadow-sm: 0 1px 3px rgba(44,30,20,0.08);
            --shadow: 0 4px 12px rgba(44,30,20,0.07);
            --shadow-md: 0 4px 16px rgba(44,30,20,0.1);
            --shadow-lg: 0 8px 30px rgba(44,30,20,0.12);
            --shadow-xl: 0 20px 60px rgba(44,30,20,0.15);
            --transition: all 0.2s ease;
        }

        body {
            font-family: 'Inter', -apple-system, sans-serif;
            min-height: 100vh;
            background: linear-gradient(135deg, #F8F6F2 0%, #F3ECE3 35%, #EFE7DC 70%, #FAF9F6 100%);
            color: var(--gray-800);
            line-height: 1.5;
            -webkit-font-smoothing: antialiased;
            overflow-x: hidden;
        }

        .admin-bg-decor {
            position: fixed; inset: 0; z-index: 0;
            pointer-events: none; overflow: hidden;
        }
        .admin-bg-decor .orb {
            position: absolute; border-radius: 50%; filter: blur(80px);
        }
        .admin-bg-decor .orb-1 {
            width: 400px; height: 400px; top: -150px; right: -100px;
            background: radial-gradient(circle, rgba(201,169,110,0.08), transparent 70%);
        }
        .admin-bg-decor .orb-2 {
            width: 350px; height: 350px; bottom: -120px; left: -80px;
            background: radial-gradient(circle, rgba(139,111,71,0.06), transparent 70%);
        }
        .admin-bg-decor .grain {
            position: fixed; inset: 0; opacity: 0.015;
            background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 256 256' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='n'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.9' numOctaves='4' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23n)'/%3E%3C/svg%3E");
            background-size: 256px 256px;
        }

        /* === TOP NAVIGATION === */
        .topnav {
            position: fixed; top: 0; left: 0; right: 0;
            height: 64px;
            background: rgba(255,255,255,0.88);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border-bottom: 1px solid #E5E7EB;
            display: flex; align-items: center;
            padding: 0 32px;
            gap: 32px;
            z-index: 200;
        }
        .topnav-brand {
            display: flex; align-items: center; gap: 10px;
            flex-shrink: 0;
        }
        .topnav-brand .brand-dot {
            width: 8px; height: 8px; border-radius: 50%;
            background: #C8A96A;
        }
        .topnav-brand .brand-name {
            font-size: 17px; font-weight: 800; color: #1A120D;
            letter-spacing: -0.3px;
        }
        .topnav-brand .brand-name span { color: #C8A96A; }

        .topnav-links {
            display: flex; align-items: center; gap: 2px;
            flex: 1;
        }
        .topnav-links .tn-link {
            padding: 8px 16px; border-radius: 8px;
            font-size: 13px; font-weight: 500; color: #6B7280;
            text-decoration: none; transition: all 0.2s;
            position: relative; white-space: nowrap;
        }
        .topnav-links .tn-link:hover { color: #000; background: rgba(0,0,0,0.04); }
        .topnav-links .tn-link.active { color: #000; font-weight: 600; }
        .topnav-links .tn-link.active::after {
            content: ''; position: absolute; bottom: -18px; left: 50%; transform: translateX(-50%);
            width: 20px; height: 2.5px; border-radius: 2px; background: #1A3B32;
            animation: hglSweep 0.5s cubic-bezier(0.4,0,0.2,1) both, hglPulse 2.8s ease-in-out 0.6s infinite;
        }
        @keyframes hglSweep {
            from { width: 0; opacity: 0; }
            to { width: 20px; opacity: 1; }
        }
        @keyframes hglPulse {
            0%, 100% { box-shadow: 0 0 4px rgba(26,59,50,0.15); }
            50% { box-shadow: 0 0 10px rgba(26,59,50,0.35); }
        }
        .topnav-links .tn-link:not(.active):hover { transform: translateY(-1px); }

        .topnav-right {
            display: flex; align-items: center; gap: 6px;
            flex-shrink: 0;
        }
        .tn-btn {
            width: 36px; height: 36px; border-radius: 10px;
            border: none; background: transparent;
            color: #6B7280; cursor: pointer;
            display: flex; align-items: center; justify-content: center;
            transition: all 0.2s;
        }
        .tn-btn:hover { background: rgba(0,0,0,0.04); color: #000; }
        .tn-btn svg { width: 18px; height: 18px; }

        .tn-avatar {
            width: 36px; height: 36px; border-radius: 10px;
            background: #0F766E; color: #fff;
            display: flex; align-items: center; justify-content: center;
            font-weight: 600; font-size: 13px; cursor: pointer;
            transition: all 0.2s; position: relative;
        }
        .tn-avatar:hover { box-shadow: 0 0 0 3px rgba(15,118,110,0.2); }

        .tn-dropdown {
            position: absolute; top: calc(100% + 8px); right: 0;
            background: #fff; border: 1px solid #E5E7EB;
            border-radius: var(--radius-sm);
            box-shadow: var(--shadow-lg);
            min-width: 200px;
            opacity: 0; visibility: hidden;
            transform: translateY(4px);
            transition: all 0.15s ease;
            z-index: 300;
            padding: 6px;
        }
        .tn-dropdown.open { opacity: 1; visibility: visible; transform: translateY(0); }
        .tn-dropdown-item {
            display: flex; align-items: center; gap: 10px;
            padding: 9px 12px; border-radius: var(--radius-xs);
            font-size: 13px; color: #374151;
            cursor: pointer; transition: all 0.15s;
            text-decoration: none; border: none; background: none;
            width: 100%; text-align: left; font-family: inherit;
        }
        .tn-dropdown-item:hover { background: #F9FAFB; color: #111; }
        .tn-dropdown-item.danger { color: #EF4444; }
        .tn-dropdown-item.danger:hover { background: #FEF2F2; }
        .tn-dropdown-item svg { width: 16px; height: 16px; flex-shrink: 0; color: #9CA3AF; }
        .tn-dropdown-divider { height: 1px; background: #F3F4F6; margin: 4px 0; }

        /* === MAIN CONTENT === */
        .main-wrap {
            padding-top: 64px;
            min-height: 100vh;
            width: 100%;
        }
        .main-content {
            width: 100%;
            position: relative;
            z-index: 1;
        }

        /* === ALERTS === */
        .alert {
            padding: 12px 18px;
            border-radius: var(--radius-sm);
            margin-bottom: 20px;
            font-size: 14px;
            display: flex;
            align-items: center;
            gap: 10px;
            font-weight: 500;
            animation: slideDown 0.3s ease;
        }
        @keyframes slideDown { from { opacity: 0; transform: translateY(-8px); } to { opacity: 1; transform: translateY(0); } }
        .alert-success { background: var(--success-bg); color: #065F46; border: 1px solid #A7F3D0; }
        .alert-error { background: var(--danger-bg); color: #991B1B; border: 1px solid #FECACA; }
        .alert::before {
            content: '';
            width: 6px; height: 6px; border-radius: 50%; flex-shrink: 0;
        }
        .alert-success::before { background: var(--success); }
        .alert-error::before { background: var(--danger); }

        /* === CARDS === */
        .card {
            background: var(--white);
            border: 1px solid var(--gray-200);
            border-radius: var(--radius);
            box-shadow: var(--shadow-sm);
            transition: var(--transition);
        }
        .card:hover { box-shadow: var(--shadow); }
        .card-header {
            padding: 20px 24px;
            border-bottom: 1px solid var(--gray-100);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .card-header h2 { font-size: 15px; font-weight: 600; color: var(--gray-900); }
        .card-body { padding: 24px; }

        /* === STATS GRID === */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 16px;
            margin-bottom: 28px;
        }
        .stat-card {
            background: var(--white);
            border: 1px solid var(--gray-200);
            border-radius: var(--radius);
            padding: 20px;
            transition: var(--transition);
        }
        .stat-card:hover {
            box-shadow: var(--shadow-md);
            transform: translateY(-2px);
        }
        .stat-card .stat-icon {
            width: 40px; height: 40px; border-radius: 12px;
            display: flex; align-items: center; justify-content: center;
            font-size: 18px; margin-bottom: 14px;
        }
        .stat-card .stat-icon.primary { background: var(--gray-100); color: var(--primary); }
        .stat-card .stat-icon.accent { background: var(--accent-light); color: var(--accent); }
        .stat-card .stat-icon.warning { background: var(--warning-bg); color: var(--warning); }
        .stat-card .stat-icon.danger { background: var(--danger-bg); color: var(--danger); }
        .stat-label { font-size: 13px; color: var(--gray-500); margin-bottom: 4px; font-weight: 500; }
        .stat-value { font-size: 28px; font-weight: 700; color: var(--gray-900); letter-spacing: -0.5px; }
        .stat-change { font-size: 12px; color: var(--gray-400); margin-top: 4px; }

        /* === BUTTONS === */
        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            padding: 10px 20px;
            border-radius: var(--radius-pill);
            font-size: 14px;
            font-weight: 500;
            cursor: pointer;
            border: none;
            transition: var(--transition);
            font-family: 'Inter', sans-serif;
            text-decoration: none;
            line-height: 1;
            position: relative;
            overflow: hidden;
        }
        .btn::after {
            content: '';
            position: absolute; inset: 0;
            background: rgba(255,255,255,0.15);
            transform: translateX(-100%);
            transition: transform 0.4s;
        }
        .btn:hover::after { transform: translateX(0); }
        .btn:active { transform: scale(0.97); }
        .btn-primary {
            background: var(--primary);
            color: #fff;
        }
        .btn-primary:hover { background: var(--primary-light); box-shadow: 0 4px 12px rgba(15,23,42,0.2); }
        .btn-accent {
            background: var(--accent);
            color: #fff;
        }
        .btn-accent:hover { background: var(--accent-dark); box-shadow: 0 4px 12px rgba(15,118,110,0.3); }
        .btn-secondary {
            background: var(--white);
            color: var(--gray-700);
            border: 1px solid var(--gray-300);
        }
        .btn-secondary:hover { background: var(--gray-50); border-color: var(--gray-400); }
        .btn-ghost {
            background: transparent;
            color: var(--gray-500);
        }
        .btn-ghost:hover { background: var(--gray-100); color: var(--gray-700); }
        .btn-danger {
            background: var(--danger);
            color: #fff;
        }
        .btn-danger:hover { box-shadow: 0 4px 12px rgba(239,68,68,0.3); }
        .btn-sm { padding: 7px 14px; font-size: 12px; }
        .btn-lg { padding: 14px 28px; font-size: 15px; }
        .btn-icon {
            width: 36px; height: 36px; padding: 0;
            justify-content: center;
        }

        /* === FORMS === */
        .form-group { margin-bottom: 20px; }
        .form-group label {
            display: block;
            font-size: 13px;
            font-weight: 600;
            color: var(--gray-700);
            margin-bottom: 6px;
        }
        .form-control {
            width: 100%;
            padding: 10px 14px;
            border: 1.5px solid var(--gray-200);
            border-radius: var(--radius-sm);
            font-size: 14px;
            font-family: 'Inter', sans-serif;
            transition: var(--transition);
            background: var(--white);
            color: var(--gray-800);
        }
        .form-control:hover { border-color: var(--gray-300); }
        .form-control:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(15,23,42,0.08);
        }
        .form-control::placeholder { color: var(--gray-400); }
        textarea.form-control { resize: vertical; min-height: 100px; }
        select.form-control {
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 12 12'%3E%3Cpath fill='%2364748B' d='M6 8L1 3h10z'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 14px center;
            padding-right: 40px;
        }
        .form-check { display: flex; align-items: center; gap: 8px; }
        .form-check input[type="checkbox"] {
            width: 16px; height: 16px; accent-color: var(--accent); cursor: pointer;
        }

        /* === TABLES === */
        .table-wrap {
            background: var(--white);
            border: 1px solid var(--gray-200);
            border-radius: var(--radius);
            overflow: hidden;
        }
        .table {
            width: 100%;
            border-collapse: collapse;
            font-size: 13px;
        }
        .table thead th {
            padding: 12px 16px;
            text-align: left;
            font-size: 11px;
            font-weight: 600;
            color: var(--gray-500);
            text-transform: uppercase;
            letter-spacing: 0.5px;
            background: var(--gray-50);
            border-bottom: 1px solid var(--gray-200);
        }
        .table tbody td {
            padding: 14px 16px;
            border-bottom: 1px solid var(--gray-100);
            vertical-align: middle;
        }
        .table tbody tr:last-child td { border-bottom: none; }
        .table tbody tr:hover { background: var(--gray-50); }

        /* === BADGES === */
        .badge {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 4px 12px;
            border-radius: var(--radius-pill);
            font-size: 11px;
            font-weight: 600;
            letter-spacing: 0.2px;
        }
        .badge-success { background: var(--success-bg); color: #065F46; }
        .badge-warning { background: var(--warning-bg); color: #92400E; }
        .badge-danger { background: var(--danger-bg); color: #991B1B; }
        .badge-info { background: var(--info-bg); color: #1E40AF; }
        .badge-neutral { background: var(--gray-100); color: var(--gray-600); }

        /* === PAGINATION === */
        .pagination {
            display: flex;
            gap: 2px;
            align-items: center;
            list-style: none;
            margin: 0;
            padding: 0;
        }
        .pagination li a, .pagination li span {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 36px;
            height: 36px;
            padding: 0 10px;
            border-radius: var(--radius-xs);
            font-size: 13px;
            font-weight: 500;
            color: var(--gray-600);
            text-decoration: none;
            transition: var(--transition);
        }
        .pagination li a:hover { background: var(--gray-100); }
        .pagination li.active span {
            background: var(--primary);
            color: #fff;
        }
        .pagination li.disabled span { color: var(--gray-300); cursor: not-allowed; }

        /* === EMPTY STATE === */
        .empty-state {
            padding: 60px 20px;
            text-align: center;
        }
        .empty-state .icon {
            width: 64px; height: 64px;
            border-radius: 16px;
            background: var(--gray-100);
            display: flex; align-items: center; justify-content: center;
            font-size: 28px; margin: 0 auto 16px;
        }
        .empty-state p { color: var(--gray-400); font-size: 14px; }

        /* === PAGE HEADER === */
        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 24px;
        }
        .page-title {
            font-size: 20px;
            font-weight: 700;
            color: var(--gray-900);
            letter-spacing: -0.3px;
        }
        .page-subtitle {
            font-size: 14px;
            color: var(--gray-500);
            margin-top: 2px;
        }

        /* === MISC === */
        .admin-badge {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 4px 12px;
            border-radius: var(--radius-pill);
            font-size: 11px;
            font-weight: 600;
            letter-spacing: 0.2px;
        }

        /* === CHAT WIDGET === */
        .chat-widget {
            position: fixed; bottom: 24px; right: 24px; z-index: 9999;
            width: 52px; height: 52px; border-radius: 50%;
            background: #1A3B32; color: #fff;
            display: flex; align-items: center; justify-content: center;
            cursor: pointer; border: none;
            box-shadow: 0 4px 20px rgba(0,0,0,0.25);
            transition: all 0.3s;
        }
        .chat-widget:hover { transform: scale(1.08); box-shadow: 0 6px 28px rgba(0,0,0,0.35); }
        .chat-widget svg { width: 22px; height: 22px; }

        /* ── Page Transition Overlay ── */
        .page-overlay {
            position: fixed; inset: 0; z-index: 99999;
            background: rgba(0,0,0,0.3); backdrop-filter: blur(4px);
            display: flex; align-items: center; justify-content: center;
            opacity: 0; pointer-events: none;
            transition: opacity 0.35s ease;
        }
        .page-overlay.active { opacity: 1; pointer-events: all; }
        .page-overlay .loader-wrap {
            display: flex; flex-direction: column; align-items: center; gap: 16px;
        }
        .page-overlay .loader-hint {
            font-size: 13px; font-weight: 500; color: rgba(255,255,255,0.65);
            letter-spacing: 0.3px; text-align: center;
        }
        .page-overlay .loader-badge {
            position: relative; width: 72px; height: 72px;
            display: flex; align-items: center; justify-content: center;
        }
        .page-overlay .loader-badge .ring {
            position: absolute; inset: 0;
            border-radius: 50%;
            border: 3px solid transparent;
            border-top-color: rgba(255,255,255,0.9);
            border-right-color: rgba(255,255,255,0.4);
            animation: spin 0.9s cubic-bezier(0.4,0,0.2,1) infinite;
        }
        .page-overlay .loader-badge .ring-2 {
            position: absolute; inset: -10px;
            border-radius: 50%;
            border: 2px solid transparent;
            border-bottom-color: rgba(26,59,50,0.7);
            border-left-color: rgba(26,59,50,0.3);
            animation: spin 1.4s cubic-bezier(0.4,0,0.2,1) infinite reverse;
        }
        .page-overlay .loader-badge .glow {
            position: absolute; inset: -24px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(26,59,50,0.35) 0%, transparent 70%);
            animation: pulseGlow 2s ease-in-out infinite;
        }
        .page-overlay .loader-badge .brand-square {
            width: 56px; height: 56px; border-radius: 14px;
            background: #1A3B32; color: #fff;
            display: flex; align-items: center; justify-content: center;
            font-size: 20px; font-weight: 800; letter-spacing: 0.5px;
            font-family: 'Inter', sans-serif; z-index: 1;
            box-shadow: 0 4px 20px rgba(26,59,50,0.3);
        }

        @keyframes spin { to { transform: rotate(360deg); } }
        @keyframes pulseGlow {
            0%, 100% { opacity: 0.6; transform: scale(0.95); }
            50% { opacity: 1; transform: scale(1.05); }
        }

        @keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
        @keyframes fadeUp { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }

        @media (max-width: 768px) {
            .topnav { padding: 0 16px; gap: 12px; }
            .topnav-links { gap: 0; overflow-x: auto; -webkit-overflow-scrolling: touch; }
            .topnav-links::-webkit-scrollbar { display: none; }
            .topnav-links .tn-link { padding: 8px 10px; font-size: 12px; }
            .main-wrap { padding-top: 64px; }
        }
    </style>
    @yield('styles')
</head>
<body>
    <div class="admin-bg-decor">
        <div class="orb orb-1"></div>
        <div class="orb orb-2"></div>
        <div class="grain"></div>
    </div>

    {{-- Top Navigation --}}
    <header class="topnav">
        <div class="topnav-brand">
            <span class="brand-dot"></span>
            <span class="brand-name">56'30 <span>Studio</span></span>
        </div>
        <nav class="topnav-links">
            @php
                $navLinks = [
                    ['route' => 'admin.dashboard', 'label' => 'Home', 'data-page' => 'Home'],
                    ['route' => 'admin.pages.index', 'label' => 'Pages', 'data-page' => 'Pages'],
                    ['route' => 'admin.bookings', 'label' => 'Bookings', 'data-page' => 'Bookings'],
                    ['route' => 'admin.services.index', 'label' => 'Services', 'data-page' => 'Services'],
                    ['route' => 'admin.addons.index', 'label' => 'Add-Ons', 'data-page' => 'Add-Ons'],
                    ['route' => 'admin.team.index', 'label' => 'Team', 'data-page' => 'Team'],
                    ['route' => 'admin.templates.index', 'label' => 'Templates', 'data-page' => 'Templates'],
                    ['route' => 'admin.analytics', 'label' => 'Analytics', 'data-page' => 'Analytics'],
                    ['route' => 'admin.polls.index', 'label' => 'Polls', 'data-page' => 'Polls'],
                    ['route' => 'admin.settings.homepage', 'label' => 'Settings', 'data-page' => 'Settings'],
                ];
            @endphp
            @foreach($navLinks as $link)
                <a href="{{ route($link['route']) }}"
                   data-page="{{ $link['data-page'] }}"
                   class="tn-link {{ request()->routeIs($link['route']) || request()->routeIs($link['route'] . '*') ? 'active' : '' }}">
                    {{ $link['label'] }}
                </a>
            @endforeach
        </nav>
        <div class="topnav-right">
            <button class="tn-btn" onclick="toggleDarkMode()" aria-label="Toggle dark mode" id="darkModeBtn">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"/></svg>
            </button>
            <button class="tn-btn" aria-label="Help">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
            </button>
            <div style="position:relative;">
                <div class="tn-avatar" onclick="toggleProfileDropdown()" id="profileBtn" tabindex="0" role="button" aria-label="Profile menu">A</div>
                <div class="tn-dropdown" id="profileDropdown">
                    <div style="padding:12px;border-bottom:1px solid #F3F4F6;margin-bottom:4px;">
                        <div style="font-weight:600;font-size:14px;color:#111;">Admin</div>
                        <div style="font-size:12px;color:#6B7280;">admin@5630studio.com</div>
                    </div>
                    <button class="tn-dropdown-item" onclick="window.location='{{ route('home') }}'">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"/><polyline points="15 3 21 3 21 9"/><line x1="10" y1="14" x2="21" y2="3"/></svg>
                        View Site
                    </button>
                    <div class="tn-dropdown-divider"></div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="tn-dropdown-item danger">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
                            Logout
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </header>

    {{-- Main Content --}}
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

    {{-- Chat Widget --}}
    <button class="chat-widget" onclick="alert('Chat support coming soon!')" aria-label="Chat support">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
    </button>

    {{-- Page Transition Overlay --}}
    <div class="page-overlay" id="pageOverlay">
        <div class="loader-wrap">
            <div class="loader-hint" id="overlayHint">Loading...</div>
            <div class="loader-badge">
                <div class="glow"></div>
                <div class="ring-2"></div>
                <div class="ring"></div>
                <div class="brand-square">56</div>
            </div>
        </div>
    </div>

    <script>
        function toggleProfileDropdown() {
            document.getElementById('profileDropdown').classList.toggle('open');
        }
        document.addEventListener('click', function(e) {
            if (!e.target.closest('#profileDropdown') && !e.target.closest('#profileBtn')) {
                document.getElementById('profileDropdown').classList.remove('open');
            }
        });

        function toggleDarkMode() {
            document.body.classList.toggle('dark-mode');
        }

        var _gs = document.getElementById('globalSearch');
        if (_gs) {
            _gs.addEventListener('input', function() {
                var q = this.value.toLowerCase();
                document.querySelectorAll('[data-search]').forEach(function(el) {
                    var txt = (el.getAttribute('data-search') || el.textContent).toLowerCase();
                    el.style.display = txt.includes(q) ? '' : 'none';
                });
            });
        }

        /* ── Page Transition Overlay ── */
        (function() {
            var overlay = document.getElementById('pageOverlay');
            var hint   = document.getElementById('overlayHint');
            if (!overlay) return;

            function showOverlay(targetPage) {
                if (targetPage) {
                    hint.textContent = targetPage.charAt(0).toUpperCase() + targetPage.slice(1) + '...';
                }
                overlay.classList.add('active');
            }

            function getPageNameFromHref(href) {
                var parts = href.replace(window.location.origin,'').replace(/\/+$/,'').split('/');
                var last = parts[parts.length-1] || 'home';
                return last.replace(/[-_]/g,' ');
            }

            document.querySelectorAll('.tn-link').forEach(function(link) {
                link.addEventListener('click', function(e) {
                    var href = this.getAttribute('href');
                    if (!href || href === '#' || href.startsWith('javascript:') || href.startsWith('#')) return;
                    if (this.getAttribute('target') === '_blank') return;
                    if (e.ctrlKey || e.metaKey || e.shiftKey) return;

                    e.preventDefault();
                    var pageName = this.getAttribute('data-page') || getPageNameFromHref(href);
                    showOverlay(pageName);

                    setTimeout(function() {
                        window.location.href = href;
                    }, 500);
                });
            });

            window.showPageOverlay = showOverlay;
        })();
    </script>
    @yield('scripts')
</body>
</html>