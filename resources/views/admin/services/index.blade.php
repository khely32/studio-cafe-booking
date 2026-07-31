@extends('admin.layout')
@section('title', 'Services')

@section('content')
<style>
    .svc-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; }
    .svc-header h1 { font-size: 20px; font-weight: 700; color: var(--gray-900); }
    .svc-grid { display: grid; grid-template-columns: 1fr; gap: 8px; }
    .svc-item {
        background: linear-gradient(135deg, rgba(255,255,255,0.85), rgba(255,255,255,0.7));
        backdrop-filter: blur(20px);
        -webkit-backdrop-filter: blur(20px);
        border: 1px solid rgba(255,255,255,0.5);
        border-radius: var(--radius);
        padding: 20px 24px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        transition: all 0.3s cubic-bezier(0.4,0,0.2,1);
        box-shadow: 0 4px 20px rgba(139,111,71,0.06);
    }
    .svc-item:hover {
        border-color: rgba(201,169,110,0.4);
        box-shadow: 0 8px 32px rgba(139,111,71,0.12);
        transform: translateY(-2px);
    }
    .svc-item-left { display: flex; align-items: center; gap: 16px; flex: 1; }
    .svc-item-thumb {
        width: 48px; height: 48px; border-radius: 12px; overflow: hidden; flex-shrink: 0;
        background: linear-gradient(135deg, var(--cafe-light), var(--cafe));
        display: flex; align-items: center; justify-content: center;
        font-weight: 700; font-size: 16px; color: #fff;
    }
    .svc-item-thumb img { width: 100%; height: 100%; object-fit: cover; }
    .svc-item-info { flex: 1; }
    .svc-item-title { font-weight: 600; font-size: 15px; color: var(--gray-900); }
    .svc-item-desc { font-size: 12px; color: var(--gray-400); margin-top: 2px; max-width: 400px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
    .svc-item-meta { display: flex; gap: 20px; align-items: center; margin-right: 20px; }
    .svc-item-meta span { font-size: 12px; color: var(--gray-500); white-space: nowrap; }
    .svc-item-meta strong { color: var(--gray-900); font-size: 15px; }
    .svc-actions { display: flex; gap: 6px; }
    .svc-actions a, .svc-actions button {
        padding: 6px 14px; border-radius: 100px; font-size: 12px; font-weight: 500;
        cursor: pointer; transition: all 0.2s; border: 1px solid rgba(0,0,0,0.06);
        background: rgba(255,255,255,0.8);
        backdrop-filter: blur(10px);
        -webkit-backdrop-filter: blur(10px);
        color: var(--gray-600); text-decoration: none; font-family: inherit;
    }
    .svc-actions a:hover, .svc-actions button:hover {
        border-color: var(--cafe); color: var(--cafe); background: rgba(201,169,110,0.1);
    }
    .svc-empty { padding: 60px 20px; text-align: center; }
    .svc-empty .icon { width: 64px; height: 64px; border-radius: 16px; background: rgba(139,111,71,0.08); display: flex; align-items: center; justify-content: center; font-size: 28px; margin: 0 auto 12px; }
    .svc-empty p { color: var(--gray-400); font-size: 14px; }
</style>

<div class="svc-header">
    <h1 style="font-family:Poppins,sans-serif;">Services & Packages</h1>
    <a href="{{ route('admin.services.create') }}" class="btn btn-primary btn-sm">+ New Service</a>
</div>

<div class="svc-grid">
    @if($services->isEmpty())
    <div class="svc-empty">
        <div class="icon">📦</div>
        <p>No services yet. Create your first package!</p>
    </div>
    @else
    @foreach($services as $service)
    <div class="svc-item">
        <div class="svc-item-left">
            <div class="svc-item-thumb">
                @if($service->image)
                <img src="{{ $service->image }}" alt="{{ $service->name }}">
                @else
                {{ strtoupper(substr($service->name, 0, 2)) }}
                @endif
            </div>
            <div class="svc-item-info">
                <div class="svc-item-title">{{ $service->name }}</div>
                <div class="svc-item-desc">{{ $service->description }}</div>
            </div>
        </div>
        <div class="svc-item-meta">
            <span><strong>₱{{ number_format($service->price, 2) }}</strong></span>
            <span>🕐 {{ $service->duration_minutes }} min</span>
            <span>👥 {{ $service->max_pax }} pax</span>
            <span class="badge {{ $service->is_active ? 'badge-success' : 'badge-neutral' }}">{{ $service->is_active ? 'Active' : 'Inactive' }}</span>
        </div>
        <div class="svc-actions">
            <a href="{{ route('admin.services.edit', $service) }}">Edit</a>
            <form method="POST" action="{{ route('admin.services.destroy', $service) }}" onsubmit="return confirm('Delete this service?')" style="display:inline;">
                @csrf @method('DELETE')
                <button type="submit">Delete</button>
            </form>
        </div>
    </div>
    @endforeach
    @endif
</div>
@endsection
