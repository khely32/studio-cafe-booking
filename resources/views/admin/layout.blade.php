<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Admin - 56'30 Studio Cafe</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        :root {
            --cafe: #8B6F47;
            --cafe-dark: #6B4F35;
            --cafe-light: #C9A96E;
            --amber: #D4A574;
            --green: #10B981;
            --green-bg: #D1FAE5;
            --orange-bg: #FEF3C7;
            --red: #EF4444;
            --red-bg: #FEE2E2;
            --gradient-1: linear-gradient(135deg, #8B6F47, #C9A96E);
            --gradient-2: linear-gradient(135deg, #6B4F35, #8B6F47);
            --gradient-3: linear-gradient(135deg, #D4A574, #C9A96E);
            --gradient-4: linear-gradient(135deg, #5C4A3A, #8B6F47);
            --bg: #FAF6F1;
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
            --radius: 14px;
            --radius-sm: 10px;
            --shadow-sm: 0 1px 3px rgba(0,0,0,0.06);
            --shadow: 0 2px 8px rgba(0,0,0,0.08);
            --shadow-md: 0 4px 16px rgba(0,0,0,0.1);
        }
        body { font-family: 'Inter', -apple-system, sans-serif; background: var(--bg); color: var(--gray-800); line-height: 1.5; }

        .topbar {
            background: #fff; border-bottom: 1px solid var(--gray-200);
            padding: 0 32px; height: 56px; display: flex; align-items: center; justify-content: space-between;
            position: sticky; top: 0; z-index: 100;
        }
        .topbar-brand { display: flex; align-items: center; gap: 10px; }
        .topbar-brand .logo {
            width: 32px; height: 32px; border-radius: 10px;
            background: var(--gradient-1); color: #fff;
            display: flex; align-items: center; justify-content: center;
            font-weight: 700; font-size: 11px;
        }
        .topbar-brand .logo svg { width: 18px; height: 18px; }
        .topbar-brand .text { font-weight: 700; font-size: 14px; color: var(--gray-900); }
        .topbar-brand .text span {
            background: var(--gradient-1); -webkit-background-clip: text;
            -webkit-text-fill-color: transparent; background-clip: text;
        }
        .chuquel-badge {
            display: none; position: fixed; bottom: 24px; right: 24px; z-index: 999;
            background: #fff; border: 1px solid var(--gray-200);
            border-radius: 16px; padding: 16px 20px;
            box-shadow: 0 4px 24px rgba(0,0,0,0.12);
            text-align: center; min-width: 160px;
            animation: fadeIn 0.5s ease;
        }
        .chuquel-badge img, .chuquel-badge svg { width: 48px; height: 48px; margin-bottom: 8px; }
        .chuquel-badge .name { font-weight: 700; font-size: 16px; color: var(--cafe); font-family: 'Georgia', serif; letter-spacing: 1px; }
        .chuquel-badge .tag { font-size: 10px; color: var(--gray-400); margin-top: 2px; }
        .topbar-right { display: flex; align-items: center; gap: 16px; }
        .topbar-right a { font-size: 13px; color: var(--gray-500); text-decoration: none; }
        .topbar-right a:hover { color: var(--cafe); }
        .avatar {
            width: 32px; height: 32px; border-radius: 10px;
            background: var(--gradient-1); color: #fff;
            display: flex; align-items: center; justify-content: center;
            font-weight: 600; font-size: 12px;
        }

        .hnav {
            background: #fff; border-bottom: 1px solid var(--gray-200);
            padding: 0 32px; display: flex; gap: 2px; align-items: center;
            position: sticky; top: 56px; z-index: 99;
        }
        .hnav-link {
            padding: 10px 18px; font-size: 13px; font-weight: 500;
            color: var(--gray-500); text-decoration: none; border-bottom: 2px solid transparent;
            transition: all 0.15s; margin-bottom: -1px;
        }
        .hnav-link:hover { color: var(--gray-700); }
        .hnav-link.active { color: var(--cafe); border-bottom-color: var(--cafe); font-weight: 600; }

        .main-content { max-width: 1100px; margin: 0 auto; padding: 28px 32px; }

        .card {
            background: #fff; border: 1px solid var(--gray-200);
            border-radius: var(--radius); box-shadow: var(--shadow-sm);
        }
        .card-header {
            padding: 18px 22px; border-bottom: 1px solid var(--gray-100);
            display: flex; justify-content: space-between; align-items: center;
        }
        .card-header h2 { font-size: 15px; font-weight: 600; }
        .card-body { padding: 22px; }

        .stats-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 14px; margin-bottom: 28px; }
        .stat-card {
            background: #fff; border: 1px solid var(--gray-200);
            border-radius: var(--radius); padding: 18px; box-shadow: var(--shadow-sm);
            position: relative; overflow: hidden;
        }
        .stat-card::before {
            content: ''; position: absolute; top: 0; left: 0; right: 0; height: 3px;
        }
        .stat-card:nth-child(1)::before { background: var(--gradient-1); }
        .stat-card:nth-child(2)::before { background: var(--gradient-2); }
        .stat-card:nth-child(3)::before { background: var(--gradient-3); }
        .stat-card:nth-child(4)::before { background: var(--gradient-4); }
        .stat-label { font-size: 12px; color: var(--gray-500); margin-bottom: 4px; }
        .stat-value { font-size: 26px; font-weight: 700; color: var(--gray-900); }

        .pages-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(260px, 1fr)); gap: 14px; }
        .page-card {
            background: #fff; border: 1px solid var(--gray-200);
            border-radius: var(--radius); padding: 18px; transition: all 0.15s;
            box-shadow: var(--shadow-sm);
        }
        .page-card:hover { box-shadow: var(--shadow-md); border-color: var(--gray-300); }
        .page-card-header { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 10px; }
        .page-card-title { font-weight: 600; font-size: 14px; color: var(--gray-900); }
        .page-card-badge {
            display: inline-flex; padding: 3px 10px; border-radius: 100px;
            font-size: 10px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.3px;
        }
        .badge-online { background: var(--green-bg); color: #065F46; }
        .badge-offline { background: var(--gray-100); color: var(--gray-500); }
        .badge-active { background: var(--green-bg); color: #065F46; }
        .badge-pending { background: var(--orange-bg); color: #92400E; }
        .badge-cancelled { background: var(--red-bg); color: #991B1B; }

        .btn {
            display: inline-flex; align-items: center; gap: 6px; padding: 7px 14px;
            border-radius: var(--radius-sm); font-size: 13px; font-weight: 500;
            cursor: pointer; border: none; transition: all 0.2s; font-family: 'Inter', sans-serif;
            position: relative; overflow: hidden;
        }
        .btn::before {
            content: ''; position: absolute; top: 0; left: -100%; width: 100%; height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.15), transparent);
            transition: left 0.5s;
        }
        .btn:hover::before { left: 100%; }
        .btn-primary {
            background: var(--gradient-1); color: #fff;
            box-shadow: 0 0 12px rgba(139,111,71,0.35);
        }
        .btn-primary:hover { box-shadow: 0 0 20px rgba(139,111,71,0.5), 0 0 40px rgba(201,169,110,0.2); transform: translateY(-1px); }
        .btn-secondary {
            background: transparent; color: var(--cafe);
            border: 1.5px solid var(--cafe-light);
            box-shadow: 0 0 8px rgba(139,111,71,0.1);
        }
        .btn-secondary:hover { background: rgba(139,111,71,0.06); box-shadow: 0 0 16px rgba(139,111,71,0.25); }
        .btn-danger {
            background: transparent; color: var(--red);
            border: 1.5px solid var(--red);
            box-shadow: 0 0 8px rgba(239,68,68,0.15);
        }
        .btn-danger:hover { background: var(--red); color: #fff; box-shadow: 0 0 20px rgba(239,68,68,0.4); }
        .btn-sm { padding: 5px 10px; font-size: 12px; }
        .btn-ghost { background: transparent; color: var(--gray-500); }
        .btn-ghost:hover { background: rgba(139,111,71,0.06); color: var(--cafe); box-shadow: 0 0 10px rgba(139,111,71,0.1); }

        .form-group { margin-bottom: 16px; }
        .form-group label { display: block; font-size: 13px; font-weight: 500; color: var(--gray-700); margin-bottom: 5px; }
        .form-control {
            width: 100%; padding: 8px 12px; border: 2px solid var(--gray-200);
            border-radius: var(--radius-sm); font-size: 13px; font-family: 'Inter', sans-serif;
            transition: all 0.15s; background: #fff;
        }
        .form-control:focus { outline: none; border-color: var(--cafe); box-shadow: 0 0 0 4px rgba(139,111,71,0.1); }
        textarea.form-control { resize: vertical; min-height: 90px; }
        select.form-control { appearance: none; background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 12 12'%3E%3Cpath fill='%2394A3B8' d='M6 8L1 3h10z'/%3E%3C/svg%3E"); background-repeat: no-repeat; background-position: right 10px center; padding-right: 32px; }
        .form-check { display: flex; align-items: center; gap: 6px; }
        .form-check input[type="checkbox"] { accent-color: var(--cafe); width: 15px; height: 15px; }

        .table { width: 100%; border-collapse: collapse; font-size: 13px; }
        .table th {
            padding: 9px 14px; text-align: left; font-size: 11px; font-weight: 600;
            color: var(--gray-500); text-transform: uppercase; letter-spacing: 0.5px;
            border-bottom: 1px solid var(--gray-200); background: var(--gray-50);
        }
        .table td { padding: 11px 14px; border-bottom: 1px solid var(--gray-100); }
        .table tr:hover { background: var(--gray-50); }

        .alert { padding: 10px 14px; border-radius: var(--radius-sm); margin-bottom: 16px; font-size: 13px; display: flex; align-items: center; gap: 8px; }
        .alert-success { background: var(--green-bg); color: #065F46; border: 1px solid #A7F3D0; }
        .alert-error { background: var(--red-bg); color: #991B1B; border: 1px solid #FECACA; }

        .empty-state { padding: 50px 20px; text-align: center; }
        .empty-state .icon { font-size: 40px; margin-bottom: 10px; opacity: 0.3; }
        .empty-state p { color: var(--gray-400); font-size: 13px; }

        .page-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 22px; }
        .page-title { font-size: 18px; font-weight: 700; color: var(--gray-900); }
        .page-subtitle { font-size: 13px; color: var(--gray-500); margin-top: 2px; }

        @media (max-width: 768px) {
            .stats-grid { grid-template-columns: repeat(2, 1fr); }
            .pages-grid { grid-template-columns: 1fr; }
            .hnav { overflow-x: auto; padding: 0 16px; }
        }
    </style>
    @yield('styles')
</head>
<body>
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
            <div class="text">chuquel</div>
        </div>
        <div class="topbar-right">
            <a href="{{ route('home') }}">View Site</a>
            <div class="avatar">A</div>
            <form method="POST" action="{{ route('logout') }}" style="display:inline;">
                @csrf
                <button type="submit" style="background:none;border:none;cursor:pointer;font-size:13px;color:var(--gray-500);font-family:inherit;padding:0;">Logout</button>
            </form>
        </div>
    </nav>

    <nav class="hnav">
        <a href="{{ route('admin.dashboard') }}" class="hnav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">Dashboard</a>
        <a href="{{ route('admin.bookings') }}" class="hnav-link {{ request()->routeIs('admin.bookings*') ? 'active' : '' }}">My Bookings</a>
        <a href="{{ route('admin.pages.index') }}" class="hnav-link {{ request()->routeIs('admin.pages.*') ? 'active' : '' }}">Pages</a>
        <a href="{{ route('admin.services.index') }}" class="hnav-link {{ request()->routeIs('admin.services.*') ? 'active' : '' }}">Services</a>
        <a href="{{ route('admin.team.index') }}" class="hnav-link {{ request()->routeIs('admin.team.*') ? 'active' : '' }}">Team</a>
        <a href="{{ route('admin.templates.index') }}" class="hnav-link {{ request()->routeIs('admin.templates.*') ? 'active' : '' }}">Templates</a>
        <a href="{{ route('admin.analytics') }}" class="hnav-link {{ request()->routeIs('admin.analytics') ? 'active' : '' }}">Analytics</a>
        <a href="{{ route('admin.polls.index') }}" class="hnav-link {{ request()->routeIs('admin.polls.*') ? 'active' : '' }}">Polls</a>
    </nav>

    <div class="main-content">
        @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if(session('error'))
        <div class="alert alert-error">{{ session('error') }}</div>
        @endif
        @yield('content')
    </div>

    <style>
        .pagination { display: flex; gap: 4px; align-items: center; list-style: none; margin: 0; padding: 0; }
        .pagination li a, .pagination li span {
            display: inline-flex; align-items: center; justify-content: center;
            min-width: 32px; height: 32px; padding: 0 8px;
            border-radius: 8px; font-size: 13px; font-weight: 500;
            color: var(--gray-600); text-decoration: none; transition: all 0.2s;
            border: 1px solid transparent;
        }
        .pagination li a:hover { background: var(--gray-100); color: var(--gray-800); }
        .pagination li.active span { background: var(--cafe); color: #fff; border-color: var(--cafe); }
        .pagination li.disabled span { color: var(--gray-300); cursor: not-allowed; }
    </style>
</body>
</html>
