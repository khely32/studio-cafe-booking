@extends('admin.layout')
@section('title', 'Pages')

@section('content')
<style>
    .pg-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; }
    .pg-header h1 { font-size: 22px; font-weight: 700; color: var(--gray-900); }
    .pg-filters { display: flex; align-items: center; gap: 12px; margin-bottom: 20px; flex-wrap: wrap; }
    .pg-filters span { font-size: 13px; font-weight: 600; color: var(--gray-700); }
    .pg-sort {
        padding: 7px 14px; border: 1px solid var(--gray-200); border-radius: var(--radius-sm);
        font-size: 13px; font-family: inherit; color: var(--gray-700); background: #fff;
    }
    .pg-sort:focus { outline: none; border-color: var(--cafe); }
    .pg-count { font-size: 13px; color: var(--gray-500); margin-left: auto; }

    .pg-list { background: #fff; border: 1px solid var(--gray-200); border-radius: var(--radius); overflow: hidden; }
    .pg-item {
        display: flex; align-items: center; justify-content: space-between;
        padding: 16px 22px; border-bottom: 1px solid var(--gray-100);
        transition: background 0.2s;
    }
    .pg-item:last-child { border-bottom: none; }
    .pg-item:hover { background: var(--gray-50); }
    .pg-item-left { display: flex; align-items: center; gap: 14px; }
    .pg-item-icon {
        width: 44px; height: 44px; border-radius: 12px;
        background: var(--gradient-1); color: #fff;
        display: flex; align-items: center; justify-content: center;
        font-weight: 700; font-size: 14px; flex-shrink: 0;
    }
    .pg-item-title { font-weight: 600; font-size: 15px; color: var(--gray-900); }
    .pg-item-url { font-size: 12px; color: var(--gray-400); margin-top: 2px; }
    .pg-item-right { display: flex; align-items: center; gap: 6px; }

    .pg-icon-btn {
        width: 34px; height: 34px; border-radius: 8px; border: 1px solid var(--gray-200);
        background: #fff; color: var(--gray-500); cursor: pointer;
        display: flex; align-items: center; justify-content: center;
        transition: all 0.2s; font-size: 15px; position: relative;
    }
    .pg-icon-btn:hover { border-color: var(--cafe); color: var(--cafe); background: rgba(139,111,71,0.04); box-shadow: 0 0 8px rgba(139,111,71,0.15); }
    .pg-icon-btn[title]:hover::after {
        content: attr(title); position: absolute; bottom: -30px; left: 50%; transform: translateX(-50%);
        background: var(--gray-900); color: #fff; font-size: 11px; padding: 4px 8px; border-radius: 4px;
        white-space: nowrap; z-index: 10; pointer-events: none;
    }

    .pg-dropdown { position: relative; }
    .pg-dropdown-menu {
        display: none; position: absolute; right: 0; top: 40px; z-index: 50;
        background: #fff; border: 1px solid var(--gray-200); border-radius: var(--radius-sm);
        box-shadow: 0 8px 30px rgba(0,0,0,0.12); min-width: 220px; padding: 6px 0;
    }
    .pg-dropdown-menu.show { display: block; }
    .pg-dropdown-item {
        display: flex; align-items: center; gap: 10px; padding: 9px 16px;
        font-size: 13px; color: var(--gray-700); cursor: pointer; transition: background 0.15s;
        text-decoration: none; border: none; background: none; width: 100%; text-align: left;
        font-family: inherit;
    }
    .pg-dropdown-item:hover { background: var(--gray-50); color: var(--gray-900); }
    .pg-dropdown-item.danger { color: var(--red); }
    .pg-dropdown-item.danger:hover { background: #FEF2F2; }
    .pg-dropdown-item .icon { width: 18px; text-align: center; font-size: 14px; flex-shrink: 0; }
    .pg-dropdown-divider { height: 1px; background: var(--gray-100); margin: 4px 0; }

    .pg-promo { display: grid; grid-template-columns: repeat(2, 1fr); gap: 14px; margin-top: 24px; }
    .pg-promo-card {
        background: #fff; border: 1px solid var(--gray-200); border-radius: var(--radius);
        padding: 24px; transition: all 0.2s; cursor: pointer;
    }
    .pg-promo-card:hover { border-color: var(--cafe-light); box-shadow: var(--shadow-md); transform: translateY(-2px); }
    .pg-promo-icon { font-size: 28px; margin-bottom: 12px; }
    .pg-promo-title { font-weight: 600; font-size: 14px; color: var(--gray-900); margin-bottom: 4px; }
    .pg-promo-desc { font-size: 12px; color: var(--gray-500); line-height: 1.6; }

    .pg-empty { padding: 60px 20px; text-align: center; color: var(--gray-400); }
    .pg-empty .icon { font-size: 40px; margin-bottom: 10px; }

    .toast { position: fixed; bottom: 24px; right: 24px; background: var(--gray-900); color: #fff; padding: 10px 20px; border-radius: 8px; font-size: 13px; z-index: 999; opacity: 0; transition: opacity 0.3s; pointer-events: none; }
    .toast.show { opacity: 1; }
</style>

<div class="pg-header">
    <h1>All booking pages</h1>
    <a href="/admin/pages/create" class="btn btn-primary" style="padding:8px 20px;font-size:13px;">+ New Page</a>
</div>

<div class="pg-filters">
    <span>Sort by</span>
    <form method="GET" style="display:flex;gap:8px;align-items:center;">
        <select name="sort" class="pg-sort" onchange="this.form.submit()">
            <option value="title" {{ request('sort')==='title'?'selected':'' }}>title</option>
            <option value="newest" {{ request('sort')==='newest'?'selected':'' }}>Newest</option>
            <option value="oldest" {{ request('sort')==='oldest'?'selected':'' }}>Oldest</option>
        </select>
    </form>
    <div class="pg-count">{{ $pages->firstItem() }} — {{ $pages->lastItem() }} of {{ $pages->total() }}</div>
</div>

<div class="pg-list">
    @if($pages->isEmpty())
    <div class="pg-empty">
        <div class="icon">📄</div>
        <p>No booking pages yet. Create your first one!</p>
    </div>
    @else
    @foreach($pages as $page)
    <div class="pg-item">
        <div class="pg-item-left">
            <div class="pg-item-icon">{{ strtoupper(substr($page->title, 0, 2)) }}</div>
            <div>
                <div class="pg-item-title">{{ $page->title }}</div>
                <div class="pg-item-url">5630studiocafe.youcanbook.me/{{ $page->slug }}</div>
            </div>
        </div>
        <div class="pg-item-right">
            <button class="pg-icon-btn" title="View page" onclick="window.open('/page/{{ $page->slug }}','_blank')">👁</button>
            <button class="pg-icon-btn" title="Copy link" onclick="copyLink(window.location.origin + '/page/{{ $page->slug }}')">🔗</button>
            <a href="/admin/pages/{{ $page->id }}/edit" class="pg-icon-btn" title="Edit settings">⚙</a>
            <div class="pg-dropdown">
                <button class="pg-icon-btn" title="Actions" onclick="toggleDropdown(this)">⋯</button>
                <div class="pg-dropdown-menu">
                    <a href="/admin/pages/{{ $page->id }}/edit" class="pg-dropdown-item">
                        <span class="icon">⚙</span> Edit settings
                    </a>
                    <button class="pg-dropdown-item" onclick="copyLink(window.location.origin + '/page/{{ $page->slug }}')">
                        <span class="icon">🔗</span> Copy link
                    </button>
                    <button class="pg-dropdown-item" onclick="window.open('/page/{{ $page->slug }}','_blank')">
                        <span class="icon">👁</span> Share view bookings
                    </button>
                    <div class="pg-dropdown-divider"></div>
                    <form method="POST" action="/admin/pages/{{ $page->id }}/toggle-publish" style="display:inline;">
                        @csrf @method('PATCH')
                        <button type="submit" class="pg-dropdown-item">
                            <span class="icon">{{ $page->is_published ? '📴' : '📶' }}</span> {{ $page->is_published ? 'Set as offline' : 'Set as online' }}
                        </button>
                    </form>
                    <button class="pg-dropdown-item" onclick="if(confirm('Create a template from this page?')){document.getElementById('tpl-form-{{ $page->id }}').submit();}">
                        <span class="icon">📋</span> Add as template
                    </button>
                    <form method="POST" action="/admin/pages/{{ $page->id }}/duplicate" id="tpl-form-{{ $page->id }}" style="display:inline;">@csrf</form>
                    <form method="POST" action="/admin/pages/{{ $page->id }}/duplicate" style="display:inline;">
                        @csrf
                        <button type="submit" class="pg-dropdown-item">
                            <span class="icon">📄</span> Duplicate
                        </button>
                    </form>
                    <div class="pg-dropdown-divider"></div>
                    <form method="POST" action="/admin/pages/{{ $page->id }}" onsubmit="return confirm('Delete this page?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="pg-dropdown-item danger">
                            <span class="icon">🗑</span> Delete
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @endforeach
    @endif
</div>

@if($pages->hasPages())
<div style="margin-top:12px;display:flex;justify-content:flex-end;">
    {{ $pages->links() }}
</div>
@endif

@if($folders->count())
<div style="margin-top:24px;">
    <h3 style="font-size:15px;font-weight:600;color:var(--gray-700);margin-bottom:12px;">Your Folders</h3>
    <div style="display:flex;gap:10px;flex-wrap:wrap;">
        @foreach($folders as $folder)
        <div style="display:flex;align-items:center;gap:8px;background:#fff;border:1px solid var(--gray-200);border-radius:var(--radius-sm);padding:8px 14px;font-size:13px;">
            <span style="width:12px;height:12px;border-radius:3px;background:{{ $folder->color }};flex-shrink:0;"></span>
            <a href="/admin/pages?folder={{ $folder->id }}" style="color:var(--gray-700);font-weight:500;text-decoration:none;">{{ $folder->name }}</a>
            <span style="color:var(--gray-400);font-size:12px;">({{ $folder->pages_count }})</span>
            <form method="POST" action="/admin/folders/{{ $folder->id }}" style="margin-left:4px;" onsubmit="return confirm('Delete this folder? Pages will be ungrouped.')">
                @csrf @method('DELETE')
                <button type="submit" style="background:none;border:none;color:var(--gray-400);cursor:pointer;font-size:12px;padding:0;line-height:1;" title="Delete folder">✕</button>
            </form>
        </div>
        @endforeach
        <a href="/admin/pages" style="font-size:13px;color:var(--cafe);text-decoration:none;line-height:34px;">Show all</a>
    </div>
</div>
@endif

<div class="pg-promo">
    <div class="pg-promo-card" onclick="document.getElementById('folderModal').style.display='flex'">
        <div class="pg-promo-icon">📁</div>
        <div class="pg-promo-title">Create a folder</div>
        <div class="pg-promo-desc">Use folders to organize and group your booking pages</div>
    </div>
    <a href="/admin/templates/create" style="text-decoration:none;">
        <div class="pg-promo-card">
            <div class="pg-promo-icon">📋</div>
            <div class="pg-promo-title">Create a template</div>
            <div class="pg-promo-desc">Save time by setting up reusable booking page templates</div>
        </div>
    </a>
    <div class="pg-promo-card" onclick="document.getElementById('slackModal').style.display='flex'">
        <div class="pg-promo-icon">💬</div>
        <div class="pg-promo-title">Connect to Slack</div>
        <div class="pg-promo-desc">
                            @if($slackUrl)
                                <span style="color:#16a34a;font-weight:600;">Connected ✓</span> — Click to update
                            @else
                                Receive notifications in your Slack channels when you take bookings
                            @endif
                        </div>
    </div>
    <a href="/admin/team" style="text-decoration:none;">
        <div class="pg-promo-card">
            <div class="pg-promo-icon">🎥</div>
            <div class="pg-promo-title">Start scheduling for your team</div>
            <div class="pg-promo-desc">Create unique Zoom meeting links. Set up a flexible, custom schedule</div>
        </div>
    </a>
</div>

<div id="folderModal" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,0.4);z-index:100;align-items:center;justify-content:center;">
    <div style="background:#fff;border-radius:var(--radius);padding:32px;width:100%;max-width:420px;box-shadow:0 20px 60px rgba(0,0,0,0.2);">
        <h3 style="font-size:18px;font-weight:700;color:var(--gray-900);margin-bottom:20px;">Create a folder</h3>
        <form method="POST" action="/admin/folders">
            @csrf
            <div style="margin-bottom:16px;">
                <label style="display:block;font-size:13px;font-weight:600;color:var(--gray-700);margin-bottom:6px;">Folder name</label>
                <input type="text" name="name" required placeholder="e.g. Promos, Corporate Events" style="width:100%;padding:10px 14px;border:1px solid var(--gray-200);border-radius:var(--radius-sm);font-size:14px;font-family:inherit;" />
            </div>
            <div style="margin-bottom:20px;">
                <label style="display:block;font-size:13px;font-weight:600;color:var(--gray-700);margin-bottom:6px;">Color</label>
                <input type="color" name="color" value="#8B6F47" style="width:50px;height:36px;border:1px solid var(--gray-200);border-radius:6px;cursor:pointer;" />
            </div>
            <div style="display:flex;gap:10px;justify-content:flex-end;">
                <button type="button" onclick="this.closest('#folderModal').style.display='none'" style="padding:8px 18px;border:1px solid var(--gray-200);border-radius:var(--radius-sm);background:#fff;font-size:13px;cursor:pointer;color:var(--gray-700);">Cancel</button>
                <button type="submit" style="padding:8px 18px;background:var(--cafe);color:#fff;border:none;border-radius:var(--radius-sm);font-size:13px;font-weight:600;cursor:pointer;">Create folder</button>
            </div>
        </form>
    </div>
</div>

<div id="slackModal" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,0.4);z-index:100;align-items:center;justify-content:center;">
    <div style="background:#fff;border-radius:var(--radius);padding:32px;width:100%;max-width:480px;box-shadow:0 20px 60px rgba(0,0,0,0.2);">
        <h3 style="font-size:18px;font-weight:700;color:var(--gray-900);margin-bottom:6px;">Connect to Slack</h3>
        <p style="font-size:13px;color:var(--gray-500);margin-bottom:20px;">Receive notifications in your Slack channel when a booking is made. You'll need a Slack Incoming Webhook URL.</p>
        <form method="POST" action="/admin/settings/slack">
            @csrf
            <div style="margin-bottom:20px;">
                <label style="display:block;font-size:13px;font-weight:600;color:var(--gray-700);margin-bottom:6px;">Webhook URL</label>
                <input type="url" name="slack_webhook_url" value="{{ $slackUrl ?? '' }}" placeholder="https://hooks.slack.com/services/..." style="width:100%;padding:10px 14px;border:1px solid var(--gray-200);border-radius:var(--radius-sm);font-size:13px;font-family:monospace;" />
            </div>
            <div style="display:flex;gap:10px;justify-content:flex-end;">
                <button type="button" onclick="this.closest('#slackModal').style.display='none'" style="padding:8px 18px;border:1px solid var(--gray-200);border-radius:var(--radius-sm);background:#fff;font-size:13px;cursor:pointer;color:var(--gray-700);">Cancel</button>
                <button type="submit" style="padding:8px 18px;background:var(--cafe);color:#fff;border:none;border-radius:var(--radius-sm);font-size:13px;font-weight:600;cursor:pointer;">Save webhook</button>
            </div>
        </form>
    </div>
</div>

<div class="toast" id="toast">Link copied!</div>

<script>
function copyLink(url) {
    navigator.clipboard.writeText(url).then(function() {
        var t = document.getElementById('toast');
        t.classList.add('show');
        setTimeout(function(){ t.classList.remove('show'); }, 2000);
    });
    closeAllDropdowns();
}
function toggleDropdown(btn) {
    var menu = btn.nextElementSibling;
    var isOpen = menu.classList.contains('show');
    closeAllDropdowns();
    if (!isOpen) menu.classList.add('show');
}
function closeAllDropdowns() {
    document.querySelectorAll('.pg-dropdown-menu').forEach(function(m){ m.classList.remove('show'); });
}
document.addEventListener('click', function(e) {
    if (!e.target.closest('.pg-dropdown')) closeAllDropdowns();
});
document.querySelectorAll('#folderModal, #slackModal').forEach(function(modal) {
    modal.addEventListener('click', function(e) {
        if (e.target === modal) modal.style.display = 'none';
    });
});
</script>
@endsection
