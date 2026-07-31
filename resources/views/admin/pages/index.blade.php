@extends('admin.layout')
@section('title', 'Booking Pages')
@section('page_title', 'All Booking Pages')

@section('content')
<style>
    :root {
        --bp-primary: #111827;
        --bp-accent: #0F766E;
        --bp-accent-hover: #115E59;
        --bp-bg: #F8FAFC;
        --bp-card-bg: #FFFFFF;
        --bp-border: #E5E7EB;
        --bp-border-light: #F1F5F9;
        --bp-text: #111827;
        --bp-text-secondary: #64748B;
        --bp-text-muted: #94A3B8;
        --bp-success: #22C55E;
        --bp-radius: 14px;
        --bp-radius-sm: 10px;
        --bp-radius-pill: 9999px;
        --bp-shadow-sm: 0 1px 2px rgba(0,0,0,0.04);
        --bp-shadow: 0 1px 3px rgba(0,0,0,0.06), 0 1px 2px rgba(0,0,0,0.04);
        --bp-shadow-md: 0 4px 16px rgba(0,0,0,0.08);
        --bp-shadow-lg: 0 8px 30px rgba(0,0,0,0.1);
        --bp-transition: all 0.2s ease;
    }

    /* ── Page Layout ── */
    .bp-wrap { display: flex; gap: 32px; align-items: flex-start; }
    .bp-main { flex: 1; min-width: 0; }
    .bp-sidebar { width: 280px; flex-shrink: 0; position: sticky; top: 96px; }
    @media (max-width: 1100px) { .bp-sidebar { display: none; } }

    /* ── Header ── */
    .bp-header {
        display: flex; align-items: center; justify-content: space-between;
        margin-bottom: 24px;
    }
    .bp-header-left { display: flex; align-items: center; gap: 12px; }
    .bp-header-left h1 {
        font-size: 24px; font-weight: 700; color: var(--bp-text);
        letter-spacing: -0.5px;
    }
    .bp-header-left .plus-icon {
        width: 28px; height: 28px; border-radius: 8px;
        background: var(--bp-accent); color: #fff;
        display: flex; align-items: center; justify-content: center;
        cursor: pointer; transition: var(--bp-transition);
        border: none; flex-shrink: 0;
    }
    .bp-header-left .plus-icon svg { width: 16px; height: 16px; }
    .bp-header-left .plus-icon:hover { background: var(--bp-accent-hover); transform: scale(1.05); }

    .bp-header-right { display: flex; align-items: center; gap: 10px; }

    .bp-btn-filter {
        display: inline-flex; align-items: center; gap: 6px;
        padding: 8px 18px; border: 1px solid var(--bp-border); border-radius: var(--bp-radius-pill);
        background: var(--bp-card-bg); color: var(--bp-text-secondary); font-size: 13px; font-weight: 500;
        cursor: pointer; font-family: 'Inter', sans-serif; transition: var(--bp-transition);
    }
    .bp-btn-filter svg { width: 16px; height: 16px; }
    .bp-btn-filter:hover { border-color: #D1D5DB; background: #F9FAFB; }

    .bp-sort-select {
        padding: 8px 32px 8px 16px;
        border: 1px solid var(--bp-border); border-radius: var(--bp-radius-pill);
        font-size: 13px; font-family: 'Inter', sans-serif; color: var(--bp-text-secondary);
        background: var(--bp-card-bg); cursor: pointer; transition: var(--bp-transition);
        appearance: none;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 12 12'%3E%3Cpath fill='%2364748B' d='M6 8L1 3h10z'/%3E%3C/svg%3E");
        background-repeat: no-repeat; background-position: right 12px center;
    }
    .bp-sort-select:focus { outline: none; border-color: #9CA3AF; }

    .bp-view-toggle {
        display: flex; gap: 2px;
        background: #F3F4F6; border-radius: var(--bp-radius-pill);
        padding: 3px;
    }
    .bp-view-btn {
        width: 34px; height: 34px; border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        border: none; background: transparent; color: #9CA3AF;
        cursor: pointer; transition: var(--bp-transition);
    }
    .bp-view-btn svg { width: 16px; height: 16px; }
    .bp-view-btn.active { background: var(--bp-card-bg); color: var(--bp-text); box-shadow: var(--bp-shadow-sm); }

    .bp-btn-create {
        display: inline-flex; align-items: center; gap: 8px;
        padding: 9px 22px; border-radius: var(--bp-radius-pill);
        background: var(--bp-accent); color: #fff;
        font-size: 13px; font-weight: 600; font-family: 'Inter', sans-serif;
        cursor: pointer; transition: var(--bp-transition);
        text-decoration: none; border: none;
        white-space: nowrap;
    }
    .bp-btn-create svg { width: 16px; height: 16px; }
    .bp-btn-create:hover { background: var(--bp-accent-hover); box-shadow: 0 4px 12px rgba(15,118,110,0.3); transform: translateY(-1px); }

    /* ── Search ── */
    .bp-search-wrap {
        position: relative; margin-bottom: 20px;
    }
    .bp-search-wrap input {
        width: 100%; padding: 10px 16px 10px 40px;
        border: 1px solid var(--bp-border); border-radius: var(--bp-radius-pill);
        font-size: 13px; font-family: 'Inter', sans-serif;
        background: var(--bp-card-bg); color: var(--bp-text);
        transition: var(--bp-transition);
    }
    .bp-search-wrap input:focus { outline: none; border-color: #9CA3AF; box-shadow: 0 0 0 3px rgba(15,118,110,0.08); }
    .bp-search-wrap input::placeholder { color: var(--bp-text-muted); }
    .bp-search-icon {
        position: absolute; left: 14px; top: 50%; transform: translateY(-50%);
        color: var(--bp-text-muted); pointer-events: none;
    }
    .bp-search-icon svg { width: 16px; height: 16px; }

    /* ── Booking Card ── */
    .bp-card-list { display: flex; flex-direction: column; gap: 12px; }

    .bp-card {
        display: flex; align-items: center; gap: 16px;
        background: var(--bp-card-bg); border: 1px solid var(--bp-border);
        border-radius: var(--bp-radius); padding: 16px 20px;
        transition: var(--bp-transition);
        animation: fadeUp 0.35s ease both;
        cursor: default;
    }
    .bp-card:hover { box-shadow: var(--bp-shadow-md); border-color: #D1D5DB; transform: translateY(-2px); }

    .bp-card-logo {
        width: 52px; height: 52px; border-radius: 14px;
        background: linear-gradient(135deg, #0F766E, #14B8A6);
        color: #fff; display: flex; align-items: center; justify-content: center;
        font-weight: 700; font-size: 18px; flex-shrink: 0;
        position: relative;
    }
    .bp-card-logo img { width: 100%; height: 100%; object-fit: cover; border-radius: 14px; }

    .bp-card-status-dot {
        position: absolute; bottom: -2px; right: -2px;
        width: 14px; height: 14px; border-radius: 50%;
        background: var(--bp-success);
        border: 3px solid var(--bp-card-bg);
    }

    .bp-card-body { flex: 1; min-width: 0; }
    .bp-card-name {
        font-weight: 600; font-size: 15px; color: var(--bp-text);
        white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
    }
    .bp-card-url {
        font-size: 12px; color: var(--bp-text-muted); margin-top: 2px;
        white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
    }
    .bp-card-url a { color: var(--bp-accent); text-decoration: none; }
    .bp-card-url a:hover { text-decoration: underline; }

    .bp-card-status {
        display: inline-flex; align-items: center; gap: 5px;
        padding: 4px 12px; border-radius: var(--bp-radius-pill);
        font-size: 11px; font-weight: 600;
    }
    .bp-card-status.published { background: #DCFCE7; color: #065F46; }
    .bp-card-status.draft { background: #F3F4F6; color: #6B7280; }

    .bp-card-actions {
        display: flex; align-items: center; gap: 6px;
        flex-shrink: 0;
    }
    .bp-card-action {
        width: 34px; height: 34px; border-radius: 10px;
        border: 1px solid transparent; background: transparent;
        color: var(--bp-text-muted); cursor: pointer;
        display: flex; align-items: center; justify-content: center;
        transition: var(--bp-transition); position: relative;
    }
    .bp-card-action svg { width: 16px; height: 16px; }
    .bp-card-action:hover { background: #F3F4F6; border-color: var(--bp-border); color: var(--bp-text-secondary); }
    .bp-card-action[data-tip]:hover::after {
        content: attr(data-tip); position: absolute; bottom: calc(100% + 8px);
        left: 50%; transform: translateX(-50%);
        background: var(--bp-text); color: #fff;
        font-size: 11px; padding: 4px 10px; border-radius: 6px;
        white-space: nowrap; z-index: 20; pointer-events: none;
        font-weight: 500;
    }

    .bp-card-more {
        position: relative;
    }
    .bp-card-dropdown {
        display: none; position: absolute; top: calc(100% + 6px); right: 0;
        z-index: 50; background: var(--bp-card-bg);
        border: 1px solid var(--bp-border); border-radius: var(--bp-radius-sm);
        box-shadow: var(--bp-shadow-lg); min-width: 180px; padding: 4px;
    }
    .bp-card-dropdown.open { display: block; animation: fadeIn 0.15s ease; }
    .bp-card-dropdown-item {
        display: flex; align-items: center; gap: 8px;
        padding: 8px 12px; border-radius: 6px;
        font-size: 12px; color: #374151;
        cursor: pointer; transition: background 0.15s;
        text-decoration: none; border: none; background: none;
        width: 100%; text-align: left; font-family: inherit;
    }
    .bp-card-dropdown-item svg { width: 14px; height: 14px; flex-shrink: 0; color: #9CA3AF; }
    .bp-card-dropdown-item:hover { background: #F9FAFB; color: var(--bp-text); }
    .bp-card-dropdown-item.danger { color: #EF4444; }
    .bp-card-dropdown-item.danger:hover { background: #FEF2F2; }
    .bp-card-dropdown-item.danger svg { color: #EF4444; }
    .bp-card-dropdown-divider { height: 1px; background: #F3F4F6; margin: 4px; }

    /* ── Sidebar ── */
    .bp-sidebar-section {
        margin-bottom: 24px;
    }
    .bp-sidebar-section:last-child { margin-bottom: 0; }

    .bp-sidebar-card {
        background: var(--bp-card-bg); border: 1px solid var(--bp-border);
        border-radius: var(--bp-radius-sm); padding: 20px;
        margin-bottom: 12px; cursor: pointer;
        transition: var(--bp-transition);
    }
    .bp-sidebar-card:last-child { margin-bottom: 0; }
    .bp-sidebar-card:hover { border-color: #D1D5DB; box-shadow: var(--bp-shadow); transform: translateY(-1px); }
    .bp-sidebar-card a { text-decoration: none; color: inherit; display: block; }

    .bp-sidebar-card .card-title {
        font-weight: 600; font-size: 14px; color: var(--bp-text);
        margin-bottom: 4px;
    }
    .bp-sidebar-card .card-desc {
        font-size: 12px; color: var(--bp-text-muted); line-height: 1.5;
    }
    .bp-sidebar-card .card-link {
        font-size: 12px; color: var(--bp-accent); font-weight: 500;
        display: inline-flex; align-items: center; gap: 4px; margin-top: 8px;
    }
    .bp-sidebar-card .card-link svg { width: 14px; height: 14px; }

    .bp-sidebar-sep {
        height: 1px; background: var(--bp-border); margin: 20px 0;
    }

    /* ── Empty State ── */
    .bp-empty {
        text-align: center; padding: 80px 20px;
        background: var(--bp-card-bg); border: 1px solid var(--bp-border);
        border-radius: var(--bp-radius);
    }
    .bp-empty svg { width: 80px; height: 80px; margin: 0 auto 20px; display: block; }
    .bp-empty h3 { font-size: 18px; font-weight: 700; color: var(--bp-text); margin-bottom: 6px; }
    .bp-empty p { font-size: 14px; color: var(--bp-text-muted); margin-bottom: 20px; }

    /* ── Toast ── */
    .bp-toast {
        position: fixed; bottom: 24px; right: 24px; z-index: 600;
        background: var(--bp-text); color: #fff;
        padding: 12px 24px; border-radius: 10px;
        font-size: 13px; font-weight: 500;
        opacity: 0; transform: translateY(10px);
        transition: all 0.3s ease; pointer-events: none;
    }
    .bp-toast.show { opacity: 1; transform: translateY(0); }

    @keyframes fadeIn { from { opacity: 0; transform: translateY(4px); } to { opacity: 1; transform: translateY(0); } }
    @keyframes fadeUp { from { opacity: 0; transform: translateY(16px); } to { opacity: 1; transform: translateY(0); } }

    /* ── Modal ── */
    .bp-modal-overlay {
        display: none; position: fixed; inset: 0; z-index: 500;
        background: rgba(0,0,0,0.4); align-items: center; justify-content: center;
        padding: 20px; backdrop-filter: blur(2px);
    }
    .bp-modal-overlay.open { display: flex; }
    .bp-modal-box {
        background: var(--bp-card-bg); border-radius: var(--bp-radius);
        padding: 28px; width: 100%; max-width: 420px;
        box-shadow: 0 25px 60px rgba(0,0,0,0.15);
        animation: fadeUp 0.25s ease;
    }
    .bp-modal-box h3 { font-size: 18px; font-weight: 700; color: var(--bp-text); margin-bottom: 4px; }
    .bp-modal-box p { font-size: 13px; color: var(--bp-text-muted); margin-bottom: 20px; }
    .bp-modal-actions { display: flex; gap: 10px; justify-content: flex-end; margin-top: 20px; padding-top: 20px; border-top: 1px solid var(--bp-border-light); }

    /* ── Responsive ── */
    @media (max-width: 1100px) {
        .bp-sidebar { width: 100%; position: static; margin-top: 28px; }
        .bp-wrap { flex-direction: column; }
    }
    @media (max-width: 768px) {
        .bp-header { flex-direction: column; align-items: flex-start; gap: 16px; }
        .bp-header-right { width: 100%; flex-wrap: wrap; }
        .bp-btn-create { width: 100%; justify-content: center; }
        .bp-card { flex-wrap: wrap; }
        .bp-card-actions { width: 100%; justify-content: flex-end; padding-top: 12px; border-top: 1px solid var(--bp-border-light); }
        .bp-search-wrap { margin-bottom: 16px; }
    }
</style>

<div class="bp-wrap">
    <div class="bp-main">
        {{-- Header --}}
        <div class="bp-header">
            <div class="bp-header-left">
                <h1>All Booking Pages</h1>
                <button class="plus-icon" onclick="window.location='{{ route('admin.pages.create') }}'" aria-label="Create page">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                </button>
            </div>
            <div class="bp-header-right">
                <button class="bp-btn-filter" onclick="document.getElementById('folderModal').classList.add('open')">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 20h16a2 2 0 0 0 2-2V8a2 2 0 0 0-2-2h-7.93a2 2 0 0 1-1.66-.9l-.82-1.2A2 2 0 0 0 7.93 3H4a2 2 0 0 0-2 2v13c0 1.1.9 2 2 2z"/></svg>
                    Filters
                </button>
                <form method="GET" style="display:inline;">
                    <select name="sort" class="bp-sort-select" onchange="this.form.submit()" aria-label="Sort pages">
                        <option value="title" {{ request('sort')==='title'?'selected':'' }}>Sort by Title</option>
                        <option value="newest" {{ request('sort')==='newest'?'selected':'' }}>Newest</option>
                        <option value="oldest" {{ request('sort')==='oldest'?'selected':'' }}>Oldest</option>
                    </select>
                </form>
                <div class="bp-view-toggle" role="group" aria-label="View toggle">
                    <button class="bp-view-btn active" id="gridViewBtn" onclick="setView('grid')" aria-label="Grid view">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/></svg>
                    </button>
                    <button class="bp-view-btn" id="listViewBtn" onclick="setView('list')" aria-label="List view">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="8" y1="6" x2="21" y2="6"/><line x1="8" y1="12" x2="21" y2="12"/><line x1="8" y1="18" x2="21" y2="18"/><line x1="3" y1="6" x2="3.01" y2="6"/><line x1="3" y1="12" x2="3.01" y2="12"/><line x1="3" y1="18" x2="3.01" y2="18"/></svg>
                    </button>
                </div>
                <a href="{{ route('admin.pages.create') }}" class="bp-btn-create">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                    Create Booking Page
                </a>
            </div>
        </div>

        {{-- Search --}}
        <div class="bp-search-wrap">
            <span class="bp-search-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg></span>
            <input type="text" id="bpSearch" placeholder="Search booking pages..." aria-label="Search booking pages">
        </div>

        {{-- Folders --}}
        @if($folders->count())
        <div style="display:flex;gap:8px;flex-wrap:wrap;margin-bottom:20px;">
            @foreach($folders as $folder)
            <a href="{{ request()->get('folder') == $folder->id ? route('admin.pages.index') : route('admin.pages.index', ['folder' => $folder->id]) }}" style="display:inline-flex;align-items:center;gap:6px;background:var(--bp-card-bg);border:1px solid var(--bp-border);border-radius:var(--bp-radius-pill);padding:6px 14px;font-size:12px;color:var(--bp-text-secondary);text-decoration:none;transition:var(--bp-transition);{{ request()->get('folder') == $folder->id ? 'border-color:' . $folder->color . ';box-shadow:0 0 0 1px ' . $folder->color . ';' : '' }}">
                <span style="width:8px;height:8px;border-radius:50%;background:{{ $folder->color }};flex-shrink:0;"></span>
                {{ $folder->name }}
                <span style="color:var(--bp-text-muted);font-size:11px;">({{ $folder->pages_count }})</span>
                @if(request()->get('folder') == $folder->id)
                <span style="color:var(--bp-accent);font-size:11px;margin-left:2px;">✓</span>
                @endif
            </a>
            @endforeach
            <button onclick="document.getElementById('folderModal').classList.add('open')" style="display:inline-flex;align-items:center;gap:6px;background:transparent;border:1px dashed var(--bp-border);border-radius:var(--bp-radius-pill);padding:6px 14px;font-size:12px;color:var(--bp-text-muted);cursor:pointer;transition:var(--bp-transition);font-family:inherit;">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="width:14px;height:14px;"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                New Folder
            </button>
        </div>
        @endif

        {{-- Content --}}
        @if($pages->isEmpty())
        <div class="bp-empty">
            <svg viewBox="0 0 80 80" fill="none" xmlns="http://www.w3.org/2000/svg">
                <rect x="16" y="18" width="48" height="36" rx="6" stroke="#D1D5DB" stroke-width="2" fill="#F9FAFB"/>
                <rect x="24" y="28" width="32" height="4" rx="2" fill="#E5E7EB"/>
                <rect x="24" y="36" width="22" height="4" rx="2" fill="#E5E7EB"/>
                <rect x="24" y="44" width="28" height="4" rx="2" fill="#E5E7EB"/>
                <circle cx="40" cy="60" r="12" fill="#E5E7EB"/>
                <path d="M36 60l3 3 5-5" stroke="#9CA3AF" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
            <h3>No booking pages yet</h3>
            <p>Get started by creating your first booking page.</p>
            <a href="{{ route('admin.pages.create') }}" class="bp-btn-create" style="display:inline-flex;">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                Create Booking Page
            </a>
        </div>
        @else
        <div class="bp-card-list" id="bpCardsList">
            @foreach($pages as $page)
            <div class="bp-card" data-search="{{ $page->title }} {{ $page->slug }}" style="animation-delay:{{ $loop->index * 0.06 }}s;">
                <div class="bp-card-logo">
                    @if($page->logo_url)
                    <img src="{{ $page->logo_url }}" alt="{{ $page->title }}">
                    @else
                    {{ strtoupper(substr($page->title, 0, 2)) }}
                    @endif
                    <span class="bp-card-status-dot"></span>
                </div>

                <div class="bp-card-body">
                    <div class="bp-card-name">{{ $page->title }}</div>
                    <div class="bp-card-url"><a href="{{ route('pages.public', $page->slug) }}" target="_blank">5630studiocafe.com/{{ $page->slug }}</a></div>
                </div>

                <span class="bp-card-status {{ $page->is_published ? 'published' : 'draft' }}">
                    {{ $page->is_published ? 'Online' : 'Draft' }}
                </span>

                <div class="bp-card-actions">
                    <button class="bp-card-action" data-tip="Open" onclick="window.open('/page/{{ $page->slug }}','_blank')">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"/><polyline points="15 3 21 3 21 9"/><line x1="10" y1="14" x2="21" y2="3"/></svg>
                    </button>
                    <button class="bp-card-action" data-tip="Copy Link" onclick="copyLink('{{ route('pages.public', $page->slug) }}')">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71"/><path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71"/></svg>
                    </button>
                    <button class="bp-card-action" data-tip="Edit" onclick="window.location='{{ route('admin.pages.edit', $page) }}'">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                    </button>
                    <div class="bp-card-more">
                        <button class="bp-card-action" data-tip="More Options" onclick="event.stopPropagation();toggleDropdown(this)">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="1"/><circle cx="19" cy="12" r="1"/><circle cx="5" cy="12" r="1"/></svg>
                        </button>
                        <div class="bp-card-dropdown">
                            <form method="POST" action="{{ route('admin.pages.duplicate', $page) }}" style="display:inline;">
                                @csrf
                                <button type="submit" class="bp-card-dropdown-item">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="9" y="9" width="13" height="13" rx="2" ry="2"/><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"/></svg>
                                    Duplicate
                                </button>
                            </form>
                            <form method="POST" action="{{ route('admin.pages.toggle-publish', $page) }}" style="display:inline;">
                                @csrf @method('PATCH')
                                <button type="submit" class="bp-card-dropdown-item">
                                    @if($page->is_published)
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="2" width="20" height="20" rx="2.18" ry="2.18"/><line x1="7" y1="2" x2="7" y2="22"/><line x1="17" y1="2" x2="17" y2="22"/><line x1="2" y1="12" x2="22" y2="12"/></svg>
                                    Set as Draft
                                    @else
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                                    Publish
                                    @endif
                                </button>
                            </form>
                            <div class="bp-card-dropdown-divider"></div>
                            <form method="POST" action="{{ route('admin.pages.destroy', $page) }}" onsubmit="return confirm('Are you sure you want to delete this booking page?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="bp-card-dropdown-item danger">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>
                                    Delete
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        {{-- Pagination --}}
        @if($pages->hasPages())
        <div style="margin-top:24px;display:flex;justify-content:flex-end;">
            {{ $pages->links() }}
        </div>
        @endif
        @endif
    </div>

    {{-- Right Sidebar --}}
    <div class="bp-sidebar">
        <div class="bp-sidebar-section">
            <div class="bp-sidebar-card" onclick="document.getElementById('folderModal').classList.add('open')">
                <div class="card-title">Create a Folder</div>
                <div class="card-desc">Use folders to organize and group your booking pages.</div>
            </div>

            <a href="{{ route('admin.templates.create') }}">
                <div class="bp-sidebar-card">
                    <div class="card-title">Create a Template</div>
                    <div class="card-desc">Save time by setting up reusable booking page templates.</div>
                </div>
            </a>

            <div class="bp-sidebar-card" onclick="document.getElementById('slackModal').classList.add('open')">
                <div class="card-title">Connect to Slack</div>
                <div class="card-desc">Receive notifications in Slack channels when you take bookings. <span style="color:var(--bp-accent);font-weight:500;">Find out how here</span></div>
            </div>

            <div class="bp-sidebar-card">
                <div class="card-title">Start Scheduling for Your Team</div>
                <div class="card-desc"><span style="color:var(--bp-accent);font-weight:500;">Read the article</span></div>
            </div>
        </div>
    </div>
</div>

{{-- Folder Modal --}}
<div class="bp-modal-overlay" id="folderModal">
    <div class="bp-modal-box">
        <h3>Create a folder</h3>
        <p>Use folders to organize and group your booking pages.</p>
        <form method="POST" action="{{ route('admin.folders.store') }}">
            @csrf
            <div class="form-group">
                <label>Folder name</label>
                <input type="text" name="name" class="form-control" required placeholder="e.g. Promos, Corporate Events">
            </div>
            <div class="form-group">
                <label>Color</label>
                <input type="color" name="color" value="#0F766E" style="width:50px;height:36px;border:1px solid var(--bp-border);border-radius:6px;cursor:pointer;">
            </div>
            <div class="bp-modal-actions">
                <button type="button" class="btn btn-secondary" onclick="this.closest('.bp-modal-overlay').classList.remove('open')">Cancel</button>
                <button type="submit" class="btn btn-accent">Create folder</button>
            </div>
        </form>
    </div>
</div>

{{-- Slack Modal --}}
<div class="bp-modal-overlay" id="slackModal">
    <div class="bp-modal-box">
        <h3>Connect to Slack</h3>
        <p>Receive notifications in your Slack channel when a booking is made. You'll need a Slack Incoming Webhook URL.</p>
        <form method="POST" action="{{ route('admin.settings.slack') }}">
            @csrf
            <div class="form-group">
                <label>Webhook URL</label>
                <input type="url" name="slack_webhook_url" class="form-control" value="{{ $slackUrl ?? '' }}" placeholder="https://hooks.slack.com/services/..." style="font-family:monospace;">
            </div>
            <div class="bp-modal-actions">
                <button type="button" class="btn btn-secondary" onclick="this.closest('.bp-modal-overlay').classList.remove('open')">Cancel</button>
                <button type="submit" class="btn btn-accent">Save webhook</button>
            </div>
        </form>
    </div>
</div>

{{-- Toast --}}
<div class="bp-toast" id="bpToast">Link copied!</div>

<script>
    // View Toggle
    function setView(view) {
        var list = document.getElementById('bpCardsList');
        var gridBtn = document.getElementById('gridViewBtn');
        var listBtn = document.getElementById('listViewBtn');
        if (view === 'grid') {
            list.style.display = '';
            gridBtn.classList.add('active'); listBtn.classList.remove('active');
        } else {
            list.style.display = '';
            listBtn.classList.add('active'); gridBtn.classList.remove('active');
        }
        try { localStorage.setItem('bp_view', view); } catch(e) {}
    }
    var savedView = localStorage.getItem('bp_view');
    if (savedView === 'list') setView('list');

    // Search
    document.getElementById('bpSearch')?.addEventListener('input', function() {
        var q = this.value.toLowerCase();
        document.querySelectorAll('[data-search]').forEach(function(el) {
            el.style.display = el.getAttribute('data-search').includes(q) ? '' : 'none';
        });
    });

    // Copy Link
    function copyLink(url) {
        navigator.clipboard.writeText(url || window.location.origin + url).then(function() {
            var t = document.getElementById('bpToast');
            if (t) { t.classList.add('show'); setTimeout(function(){ t.classList.remove('show'); }, 2000); }
        });
    }

    // Dropdown
    function toggleDropdown(btn) {
        var menu = btn.nextElementSibling;
        var isOpen = menu.classList.contains('open');
        closeAllDropdowns();
        if (!isOpen) menu.classList.add('open');
    }
    function closeAllDropdowns() {
        document.querySelectorAll('.bp-card-dropdown').forEach(function(m){ m.classList.remove('open'); });
    }
    document.addEventListener('click', function(e) {
        if (!e.target.closest('.bp-card-dropdown') && !e.target.closest('.bp-card-more')) closeAllDropdowns();
    });

    // Modal close
    document.querySelectorAll('.bp-modal-overlay').forEach(function(m) {
        m.addEventListener('click', function(e) { if (e.target === m) m.classList.remove('open'); });
    });
</script>
@endsection
