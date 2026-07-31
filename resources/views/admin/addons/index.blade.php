@extends('admin.layout')
@section('title', 'Add-Ons')

@section('content')
<style>
    .addon-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; }
    .addon-header h1 { font-size: 20px; font-weight: 700; color: var(--gray-900); }
    .addon-grid { display: grid; grid-template-columns: 1fr; gap: 8px; }
    .addon-group { margin-bottom: 4px; }
    .addon-group-title {
        font-size: 13px; font-weight: 700; color: var(--gray-900);
        padding: 10px 24px 6px; letter-spacing: 0.3px;
    }
    .addon-item {
        background: linear-gradient(135deg, rgba(255,255,255,0.85), rgba(255,255,255,0.7));
        backdrop-filter: blur(20px);
        -webkit-backdrop-filter: blur(20px);
        border: 1px solid rgba(255,255,255,0.5);
        border-radius: var(--radius-sm);
        padding: 14px 24px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        transition: all 0.3s cubic-bezier(0.4,0,0.2,1);
        box-shadow: 0 4px 20px rgba(139,111,71,0.06);
    }
    .addon-item:hover {
        border-color: rgba(201,169,110,0.4);
        box-shadow: 0 8px 32px rgba(139,111,71,0.12);
        transform: translateY(-1px);
    }
    .addon-item-info { flex: 1; }
    .addon-item-title { font-weight: 600; font-size: 14px; color: var(--gray-900); }
    .addon-item-desc { font-size: 12px; color: var(--gray-400); margin-top: 2px; }
    .addon-item-meta { display: flex; gap: 16px; align-items: center; margin-right: 20px; }
    .addon-item-meta span { font-size: 12px; color: var(--gray-500); white-space: nowrap; }
    .addon-item-meta strong { color: var(--gray-900); font-size: 15px; }
    .addon-actions { display: flex; gap: 6px; }
    .addon-actions a, .addon-actions button {
        padding: 6px 14px; border-radius: 100px; font-size: 12px; font-weight: 500;
        cursor: pointer; transition: all 0.2s; border: 1px solid rgba(0,0,0,0.06);
        background: rgba(255,255,255,0.8);
        backdrop-filter: blur(10px);
        -webkit-backdrop-filter: blur(10px);
        color: var(--gray-600); text-decoration: none; font-family: inherit;
    }
    .addon-actions a:hover, .addon-actions button:hover {
        border-color: var(--cafe); color: var(--cafe); background: rgba(201,169,110,0.1);
    }
    .addon-empty { padding: 60px 20px; text-align: center; }
    .addon-empty .icon { width: 64px; height: 64px; border-radius: 16px; background: rgba(139,111,71,0.08); display: flex; align-items: center; justify-content: center; font-size: 28px; margin: 0 auto 12px; }
    .addon-empty p { color: var(--gray-400); font-size: 14px; }
</style>

<div class="addon-header">
    <h1 style="font-family:Poppins,sans-serif;">Add-Ons</h1>
    <a href="{{ route('admin.addons.create') }}" class="btn btn-primary btn-sm">+ New Add-On</a>
</div>

<div class="addon-grid">
    @php
        $groups = [];
        foreach ($addons as $a) {
            $key = $a->category ?: '@' . $a->id;
            if (!isset($groups[$key])) {
                $groups[$key] = ['category' => $a->category, 'items' => []];
            }
            $groups[$key]['items'][] = $a;
        }
    @endphp

    @if(empty($groups))
    <div class="addon-empty">
        <div class="icon">🧾</div>
        <p>No add-ons yet. Create your first one!</p>
    </div>
    @else
    @foreach($groups as $group)
        <div class="addon-group">
            @if($group['category'])
            <div class="addon-group-title">{{ $group['category'] }}</div>
            @endif
            @foreach($group['items'] as $addon)
            <div class="addon-item">
                <div class="addon-item-info">
                    <div class="addon-item-title">{{ $addon->name }}</div>
                    @if($addon->description)
                    <div class="addon-item-desc">{{ $addon->description }}</div>
                    @endif
                </div>
                <div class="addon-item-meta">
                    <span><strong>{{ $addon->price > 0 ? '₱' . number_format($addon->price, 0) : 'FREE' }}</strong></span>
                    <span class="badge {{ $addon->is_active ? 'badge-success' : 'badge-neutral' }}">{{ $addon->is_active ? 'Active' : 'Inactive' }}</span>
                </div>
                <div class="addon-actions">
                    <a href="{{ route('admin.addons.edit', $addon) }}">Edit</a>
                    <form method="POST" action="{{ route('admin.addons.destroy', $addon) }}" onsubmit="return confirm('Delete this add-on?')" style="display:inline;">
                        @csrf @method('DELETE')
                        <button type="submit">Delete</button>
                    </form>
                </div>
            </div>
            @endforeach
        </div>
    @endforeach
    @endif
</div>
@endsection
