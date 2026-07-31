@extends('admin.layout')
@section('title', 'Team')

@section('content')
<div class="page-header">
    <div>
        <div class="page-title">Team</div>
        <div class="page-subtitle">Manage your studio team members</div>
    </div>
    <a href="{{ route('admin.team.create') }}" class="btn btn-primary btn-sm">+ New Member</a>
</div>

@if($members->isEmpty())
<div style="background:linear-gradient(135deg,rgba(255,255,255,0.85),rgba(255,255,255,0.6));backdrop-filter:blur(20px);-webkit-backdrop-filter:blur(20px);border:1px solid rgba(255,255,255,0.5);border-radius:var(--radius);padding:60px 20px;text-align:center;color:var(--gray-400);font-size:14px;box-shadow:0 10px 35px rgba(139,111,71,0.06);">No team members yet.</div>
@else
<div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(340px,1fr));gap:16px;">
    @foreach($members as $m)
    <div style="background:linear-gradient(135deg,rgba(255,255,255,0.85),rgba(255,255,255,0.6));backdrop-filter:blur(20px);-webkit-backdrop-filter:blur(20px);border:1px solid rgba(255,255,255,0.5);border-radius:var(--radius);padding:20px;box-shadow:0 4px 20px rgba(139,111,71,0.06);transition:all 0.3s cubic-bezier(0.4,0,0.2,1);" onmouseover="this.style.transform='translateY(-3px)';this.style.boxShadow='0 12px 40px rgba(139,111,71,0.12)';this.style.borderColor='rgba(201,169,110,0.3)'" onmouseout="this.style.transform='';this.style.boxShadow='';this.style.borderColor=''">
        <div>
            <div style="display:flex;align-items:center;gap:14px;margin-bottom:16px;">
                <div style="width:44px;height:44px;border-radius:12px;background:var(--gradient-1);display:flex;align-items:center;justify-content:center;font-weight:700;color:#fff;font-size:16px;flex-shrink:0;">{{ strtoupper(substr($m->name,0,1)) }}</div>
                <div style="flex:1;">
                    <div style="font-weight:600;font-size:15px;color:var(--gray-900);font-family:Poppins,sans-serif;">{{ $m->name }}</div>
                    <div style="font-size:12px;color:var(--gray-500);">{{ $m->role }}</div>
                </div>
                <span class="badge {{ $m->is_active ? 'badge-success' : 'badge-neutral' }}">{{ $m->is_active ? 'Active' : 'Inactive' }}</span>
            </div>
            @if($m->email)
            <div style="font-size:13px;color:var(--gray-500);margin-bottom:4px;">{{ $m->email }}</div>
            @endif
            @if($m->phone)
            <div style="font-size:13px;color:var(--gray-500);margin-bottom:16px;">{{ $m->phone }}</div>
            @elseif(!$m->email)
            <div style="margin-bottom:16px;"></div>
            @endif
            <div style="display:flex;gap:8px;padding-top:16px;border-top:1px solid rgba(0,0,0,0.05);">
                <a href="{{ route('admin.team.edit', $m) }}" class="btn btn-secondary btn-sm">Edit</a>
                <form method="POST" action="{{ route('admin.team.destroy', $m) }}" onsubmit="return confirm('Delete this member?')">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn btn-ghost btn-sm" style="color:var(--gray-500);">Delete</button>
                </form>
            </div>
        </div>
    </div>
    @endforeach
</div>
@endif
@endsection
