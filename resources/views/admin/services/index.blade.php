@extends('admin.layout')
@section('title', 'Services')

@section('content')
<style>
    .svc-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; }
    .svc-header h1 { font-size: 22px; font-weight: 700; color: var(--gray-900); }
    .svc-list { background: #fff; border: 1px solid var(--gray-200); border-radius: var(--radius); overflow: hidden; }
    .svc-item {
        display: flex; align-items: center; justify-content: space-between;
        padding: 18px 22px; border-bottom: 1px solid var(--gray-100);
        transition: background 0.2s;
    }
    .svc-item:last-child { border-bottom: none; }
    .svc-item:hover { background: var(--gray-50); }
    .svc-item-left { display: flex; align-items: center; gap: 14px; flex: 1; }
    .svc-item-icon {
        width: 42px; height: 42px; border-radius: 12px;
        background: var(--gradient-1); color: #fff;
        display: flex; align-items: center; justify-content: center;
        font-weight: 700; font-size: 14px; flex-shrink: 0;
    }
    .svc-item-info { flex: 1; }
    .svc-item-title { font-weight: 600; font-size: 15px; color: var(--gray-900); }
    .svc-item-desc { font-size: 12px; color: var(--gray-500); margin-top: 2px; max-width: 400px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
    .svc-item-meta { display: flex; gap: 16px; align-items: center; margin-right: 20px; }
    .svc-item-meta span { font-size: 12px; color: var(--gray-500); white-space: nowrap; }
    .svc-item-meta strong { color: var(--cafe-dark); font-size: 14px; }
    .svc-actions { display: flex; gap: 6px; }
    .svc-actions a, .svc-actions button {
        padding: 5px 12px; border-radius: var(--radius-sm); font-size: 11px; font-weight: 500;
        cursor: pointer; transition: all 0.2s; border: 1px solid var(--gray-200);
        background: #fff; color: var(--gray-600); text-decoration: none; font-family: inherit;
    }
    .svc-actions a:hover, .svc-actions button:hover {
        border-color: var(--cafe); color: var(--cafe); background: rgba(139,111,71,0.04);
    }
    .svc-status { padding: 3px 10px; border-radius: 100px; font-size: 10px; font-weight: 600; text-transform: uppercase; }
    .svc-active { background: #D1FAE5; color: #065F46; }
    .svc-inactive { background: var(--gray-100); color: var(--gray-500); }
    .svc-empty { padding: 60px 20px; text-align: center; color: var(--gray-400); }
</style>

<div class="svc-header">
    <h1>Services & Packages</h1>
    <a href="{{ route('admin.services.create') }}" class="btn btn-primary" style="padding:8px 20px;font-size:13px;">+ New Service</a>
</div>

<div class="svc-list">
    @if($services->isEmpty())
    <div class="svc-empty">
        <p>No services yet. Create your first package!</p>
    </div>
    @else
    @foreach($services as $service)
    <div class="svc-item">
        <div class="svc-item-left">
            <div class="svc-item-icon">{{ strtoupper(substr($service->name, 0, 2)) }}</div>
            <div class="svc-item-info">
                <div class="svc-item-title">{{ $service->name }}</div>
                <div class="svc-item-desc">{{ $service->description }}</div>
            </div>
        </div>
        <div class="svc-item-meta">
            <span><strong>₱{{ number_format($service->price, 2) }}</strong></span>
            <span>🕐 {{ $service->duration_minutes }} min</span>
            <span>👥 {{ $service->max_pax }} pax</span>
            <span class="svc-status {{ $service->is_active ? 'svc-active' : 'svc-inactive' }}">{{ $service->is_active ? 'Active' : 'Inactive' }}</span>
        </div>
        <div class="svc-actions">
            <a href="{{ route('admin.services.edit', $service) }}">Edit</a>
            <form method="POST" action="{{ route('admin.services.destroy', $service) }}" onsubmit="return confirm('Delete this service?')" style="display:inline;">@csrf @method('DELETE')<button type="submit">Delete</button></form>
        </div>
    </div>
    @endforeach
    @endif
</div>
@endsection
