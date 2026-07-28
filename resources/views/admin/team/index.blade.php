@extends('admin.layout')
@section('title', 'Team')

@section('content')
<div class="page-header">
    <div>
        <div class="page-title">Team</div>
        <div class="page-subtitle">Manage your studio team members</div>
    </div>
    <a href="{{ route('admin.team.create') }}" class="btn btn-primary">+ New Member</a>
</div>

<div class="pages-grid">
    @if($members->isEmpty())
    <div class="empty-state" style="grid-column:1/-1;"><div class="icon">👥</div><p>No team members yet.</p></div>
    @else
    @foreach($members as $m)
    <div class="page-card">
        <div class="page-card-header">
            <div style="display:flex;align-items:center;gap:12px;">
                <div style="width:40px;height:40px;border-radius:50%;background:var(--primary-bg);display:flex;align-items:center;justify-content:center;font-weight:600;color:var(--primary);font-size:14px;">{{ strtoupper(substr($m->name,0,1)) }}</div>
                <div>
                    <div class="page-card-title">{{ $m->name }}</div>
                    <div class="page-card-subtitle">{{ $m->role }}</div>
                </div>
            </div>
            <span class="page-card-badge {{ $m->is_active ? 'badge-online' : 'badge-offline' }}">{{ $m->is_active ? 'Active' : 'Inactive' }}</span>
        </div>
        <div class="page-card-meta">
            @if($m->email)<div class="page-card-meta-item">{{ $m->email }}</div>@endif
            @if($m->phone)<div class="page-card-meta-item">{{ $m->phone }}</div>@endif
        </div>
        <div style="margin-top:16px;display:flex;gap:8px;">
            <a href="{{ route('admin.team.edit', $m) }}" class="btn btn-sm btn-secondary">Edit</a>
            <form method="POST" action="{{ route('admin.team.destroy', $m) }}" onsubmit="return confirm('Delete?')">@csrf @method('DELETE')<button type="submit" class="btn btn-sm btn-danger">Delete</button></form>
        </div>
    </div>
    @endforeach
    @endif
</div>
@endsection
