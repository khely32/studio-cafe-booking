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
            --primary: #4F46E5;
            --primary-light: #818CF8;
            --primary-bg: #EEF2FF;
            --green: #10B981;
            --green-bg: #D1FAE5;
            --orange: #F59E0B;
            --orange-bg: #FEF3C7;
            --red: #EF4444;
            --red-bg: #FEE2E2;
            --gray-50: #F9FAFB;
            --gray-100: #F3F4F6;
            --gray-200: #E5E7EB;
            --gray-300: #D1D5DB;
            --gray-400: #9CA3AF;
            --gray-500: #6B7280;
            --gray-600: #4B5563;
            --gray-700: #374151;
            --gray-800: #1F2937;
            --gray-900: #111827;
            --radius: 12px;
            --radius-sm: 8px;
            --shadow-sm: 0 1px 2px rgba(0,0,0,0.05);
            --shadow: 0 1px 3px rgba(0,0,0,0.1), 0 1px 2px rgba(0,0,0,0.06);
            --shadow-md: 0 4px 6px -1px rgba(0,0,0,0.1), 0 2px 4px -1px rgba(0,0,0,0.06);
        }
        body { font-family: 'Inter', -apple-system, sans-serif; background: var(--gray-50); color: var(--gray-800); line-height: 1.5; }

        /* Top Nav */
        .topnav {
            background: #fff; border-bottom: 1px solid var(--gray-200);
            padding: 0 32px; height: 60px; display: flex; align-items: center; justify-content: space-between;
            position: sticky; top: 0; z-index: 100;
        }
        .topnav-brand { display: flex; align-items: center; gap: 12px; }
        .topnav-brand .logo {
            width: 32px; height: 32px; border-radius: 8px;
            background: var(--primary); color: #fff; display: flex; align-items: center; justify-content: center;
            font-weight: 700; font-size: 12px;
        }
        .topnav-brand .text { font-weight: 700; font-size: 15px; color: var(--gray-900); }
        .topnav-brand .text span { color: var(--primary); }

        .topnav-tabs { display: flex; gap: 4px; }
        .topnav-tab {
            padding: 8px 16px; border-radius: var(--radius-sm); font-size: 14px; font-weight: 500;
            color: var(--gray-500); text-decoration: none; transition: all 0.15s;
        }
        .topnav-tab:hover { background: var(--gray-100); color: var(--gray-700); }
        .topnav-tab.active { background: var(--primary-bg); color: var(--primary); }

        .topnav-right { display: flex; align-items: center; gap: 16px; }
        .topnav-right a { font-size: 13px; color: var(--gray-500); text-decoration: none; }
        .topnav-right a:hover { color: var(--gray-700); }
        .avatar {
            width: 32px; height: 32px; border-radius: 50%; background: var(--primary-bg);
            display: flex; align-items: center; justify-content: center;
            font-weight: 600; font-size: 13px; color: var(--primary);
        }

        /* Main Content */
        .main { max-width: 1200px; margin: 0 auto; padding: 32px; }

        /* Cards */
        .card {
            background: #fff; border: 1px solid var(--gray-200);
            border-radius: var(--radius); box-shadow: var(--shadow-sm);
        }
        .card-header {
            padding: 20px 24px; border-bottom: 1px solid var(--gray-100);
            display: flex; justify-content: space-between; align-items: center;
        }
        .card-header h2 { font-size: 16px; font-weight: 600; }
        .card-body { padding: 24px; }

        /* Stats Grid */
        .stats-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 16px; margin-bottom: 32px; }
        .stat-card {
            background: #fff; border: 1px solid var(--gray-200);
            border-radius: var(--radius); padding: 20px; box-shadow: var(--shadow-sm);
        }
        .stat-label { font-size: 13px; color: var(--gray-500); margin-bottom: 4px; }
        .stat-value { font-size: 28px; font-weight: 700; color: var(--gray-900); }
        .stat-change { font-size: 12px; margin-top: 4px; }
        .stat-change.positive { color: var(--green); }
        .stat-change.negative { color: var(--red); }

        /* Grid View (YCBM style booking pages) */
        .pages-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 16px; }
        .page-card {
            background: #fff; border: 1px solid var(--gray-200);
            border-radius: var(--radius); padding: 20px; transition: all 0.15s;
            box-shadow: var(--shadow-sm); cursor: pointer; position: relative;
        }
        .page-card:hover { box-shadow: var(--shadow-md); border-color: var(--gray-300); }
        .page-card-header { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 12px; }
        .page-card-title { font-weight: 600; font-size: 15px; color: var(--gray-900); }
        .page-card-subtitle { font-size: 13px; color: var(--gray-500); margin-top: 2px; }
        .page-card-badge {
            display: inline-flex; padding: 3px 10px; border-radius: 100px;
            font-size: 11px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.3px;
        }
        .badge-online { background: var(--green-bg); color: #065F46; }
        .badge-offline { background: var(--gray-100); color: var(--gray-500); }
        .badge-active { background: var(--green-bg); color: #065F46; }
        .badge-draft { background: var(--gray-100); color: var(--gray-500); }
        .badge-pending { background: var(--orange-bg); color: #92400E; }
        .badge-confirmed { background: var(--green-bg); color: #065F46; }
        .badge-cancelled { background: var(--red-bg); color: #991B1B; }

        .page-card-meta { display: flex; gap: 16px; margin-top: 12px; padding-top: 12px; border-top: 1px solid var(--gray-100); }
        .page-card-meta-item { font-size: 12px; color: var(--gray-500); }
        .page-card-meta-item strong { color: var(--gray-700); }

        /* Toggle Switch */
        .toggle { position: relative; display: inline-block; width: 44px; height: 24px; }
        .toggle input { opacity: 0; width: 0; height: 0; }
        .toggle-slider {
            position: absolute; cursor: pointer; top: 0; left: 0; right: 0; bottom: 0;
            background: var(--gray-300); border-radius: 24px; transition: 0.2s;
        }
        .toggle-slider:before {
            content: ""; position: absolute; height: 18px; width: 18px; left: 3px; bottom: 3px;
            background: #fff; border-radius: 50%; transition: 0.2s;
        }
        .toggle input:checked + .toggle-slider { background: var(--green); }
        .toggle input:checked + .toggle-slider:before { transform: translateX(20px); }

        /* Buttons */
        .btn {
            display: inline-flex; align-items: center; gap: 6px; padding: 8px 16px;
            border-radius: var(--radius-sm); font-size: 13px; font-weight: 500;
            cursor: pointer; border: none; transition: all 0.15s; font-family: 'Inter', sans-serif;
        }
        .btn-primary { background: var(--primary); color: #fff; }
        .btn-primary:hover { background: #4338CA; }
        .btn-secondary { background: var(--gray-100); color: var(--gray-700); border: 1px solid var(--gray-200); }
        .btn-secondary:hover { background: var(--gray-200); }
        .btn-danger { background: var(--red-bg); color: var(--red); }
        .btn-danger:hover { background: var(--red); color: #fff; }
        .btn-sm { padding: 6px 12px; font-size: 12px; }
        .btn-ghost { background: transparent; color: var(--gray-500); }
        .btn-ghost:hover { background: var(--gray-100); color: var(--gray-700); }

        /* Forms */
        .form-group { margin-bottom: 16px; }
        .form-group label { display: block; font-size: 13px; font-weight: 500; color: var(--gray-700); margin-bottom: 6px; }
        .form-control {
            width: 100%; padding: 9px 12px; border: 1px solid var(--gray-300);
            border-radius: var(--radius-sm); font-size: 14px; font-family: 'Inter', sans-serif;
            transition: all 0.15s; background: #fff;
        }
        .form-control:focus { outline: none; border-color: var(--primary); box-shadow: 0 0 0 3px var(--primary-bg); }
        textarea.form-control { resize: vertical; min-height: 100px; }
        select.form-control { appearance: none; background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 12 12'%3E%3Cpath fill='%236B7280' d='M6 8L1 3h10z'/%3E%3C/svg%3E"); background-repeat: no-repeat; background-position: right 12px center; padding-right: 36px; }
        .form-check { display: flex; align-items: center; gap: 8px; }
        .form-check input[type="checkbox"] { accent-color: var(--primary); width: 16px; height: 16px; }

        /* Table */
        .table { width: 100%; border-collapse: collapse; font-size: 14px; }
        .table th {
            padding: 10px 16px; text-align: left; font-size: 12px; font-weight: 600;
            color: var(--gray-500); text-transform: uppercase; letter-spacing: 0.5px;
            border-bottom: 1px solid var(--gray-200); background: var(--gray-50);
        }
        .table td { padding: 12px 16px; border-bottom: 1px solid var(--gray-100); }
        .table tr:hover { background: var(--gray-50); }

        /* Alerts */
        .alert { padding: 12px 16px; border-radius: var(--radius-sm); margin-bottom: 16px; font-size: 14px; display: flex; align-items: center; gap: 8px; }
        .alert-success { background: var(--green-bg); color: #065F46; border: 1px solid #A7F3D0; }
        .alert-error { background: var(--red-bg); color: #991B1B; border: 1px solid #FECACA; }

        /* Empty State */
        .empty-state { padding: 60px 20px; text-align: center; }
        .empty-state .icon { font-size: 48px; margin-bottom: 12px; opacity: 0.3; }
        .empty-state p { color: var(--gray-400); font-size: 14px; }

        /* Page Layout */
        .page-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; }
        .page-title { font-size: 20px; font-weight: 700; color: var(--gray-900); }
        .page-subtitle { font-size: 14px; color: var(--gray-500); margin-top: 2px; }

        /* Folder Panel (YCBM style) */
        .layout-with-sidebar { display: grid; grid-template-columns: 1fr 240px; gap: 24px; }
        .folder-panel { position: sticky; top: 92px; align-self: start; }
        .folder-panel h3 { font-size: 12px; font-weight: 600; color: var(--gray-400); text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 12px; }
        .folder-item {
            display: flex; align-items: center; gap: 8px; padding: 8px 12px;
            border-radius: var(--radius-sm); font-size: 14px; color: var(--gray-600);
            cursor: pointer; transition: all 0.15s; text-decoration: none;
        }
        .folder-item:hover { background: var(--gray-100); color: var(--gray-800); }
        .folder-item.active { background: var(--primary-bg); color: var(--primary); font-weight: 500; }
        .folder-item .count { margin-left: auto; font-size: 12px; color: var(--gray-400); }

        /* Search */
        .search-box { position: relative; }
        .search-box input { padding-left: 36px; }
        .search-box .icon { position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: var(--gray-400); font-size: 14px; }

        @media (max-width: 768px) {
            .stats-grid { grid-template-columns: repeat(2, 1fr); }
            .pages-grid { grid-template-columns: 1fr; }
            .layout-with-sidebar { grid-template-columns: 1fr; }
            .topnav-tabs { display: none; }
        }
    </style>
    @yield('styles')
</head>
<body>
    <nav class="topnav">
        <div class="topnav-brand">
            <div class="logo">56</div>
            <div class="text">56'30 <span>Studio</span></div>
        </div>
        <div class="topnav-tabs">
            <a href="{{ route('admin.dashboard') }}" class="topnav-tab {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">Dashboard</a>
            <a href="{{ route('admin.bookings') }}" class="topnav-tab {{ request()->routeIs('admin.bookings*') ? 'active' : '' }}">Bookings</a>
            <a href="{{ route('admin.pages.index') }}" class="topnav-tab {{ request()->routeIs('admin.pages.*') ? 'active' : '' }}">Pages</a>
            <a href="{{ route('admin.team.index') }}" class="topnav-tab {{ request()->routeIs('admin.team.*') ? 'active' : '' }}">Team</a>
            <a href="{{ route('admin.analytics') }}" class="topnav-tab {{ request()->routeIs('admin.analytics') ? 'active' : '' }}">Analytics</a>
            <a href="{{ route('admin.polls.index') }}" class="topnav-tab {{ request()->routeIs('admin.polls.*') ? 'active' : '' }}">Polls</a>
            <a href="{{ route('admin.templates.index') }}" class="topnav-tab {{ request()->routeIs('admin.templates.*') ? 'active' : '' }}">Templates</a>
        </div>
        <div class="topnav-right">
            <a href="{{ route('home') }}">View Site</a>
            <div class="avatar">A</div>
            <form method="POST" action="{{ route('logout') }}" style="display:inline;">
                @csrf
                <button type="submit" style="background:none;border:none;cursor:pointer;font-size:13px;color:var(--gray-500);font-family:inherit;padding:0;">Logout</button>
            </form>
        </div>
    </nav>

    <div class="main">
        @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if(session('error'))
        <div class="alert alert-error">{{ session('error') }}</div>
        @endif
        @yield('content')
    </div>
</body>
</html>
