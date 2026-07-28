@extends('admin.layout')
@section('title', 'Pages')

@section('content')
<div class="page-header">
    <div>
        <div class="page-title">Pages</div>
        <div class="page-subtitle">Manage your booking page content</div>
    </div>
    <a href="{{ route('admin.pages.create') }}" class="btn btn-primary">+ New Page</a>
</div>

<div class="pages-grid">
    @if($pages->isEmpty())
    <div class="empty-state" style="grid-column:1/-1;"><div class="icon">📄</div><p>No pages yet.</p></div>
    @else
    @foreach($pages as $page)
    <div class="page-card">
        <div class="page-card-header">
            <div>
                <div class="page-card-title">{{ $page->title }}</div>
                <div class="page-card-subtitle">/{{ $page->slug }}</div>
            </div>
            <span class="page-card-badge {{ $page->is_published ? 'badge-online' : 'badge-offline' }}">{{ $page->is_published ? 'Published' : 'Draft' }}</span>
        </div>
        <div class="page-card-meta">
            <div class="page-card-meta-item">Updated <strong>{{ $page->updated_at->diffForHumans() }}</strong></div>
        </div>
        <div style="margin-top:16px;display:flex;gap:8px;">
            <a href="{{ route('admin.pages.edit', $page) }}" class="btn btn-sm btn-secondary">Edit</a>
            <form method="POST" action="{{ route('admin.pages.destroy', $page) }}" onsubmit="return confirm('Delete this page?')">@csrf @method('DELETE')<button type="submit" class="btn btn-sm btn-danger">Delete</button></form>
        </div>
    </div>
    @endforeach
    @endif
</div>
@endsection
