@extends('admin.layout')
@section('title', 'Edit: {{ $member->name }}')

@section('content')
<div class="page-header">
    <div>
        <div class="page-title">Edit Team Member</div>
        <div class="page-subtitle">{{ $member->name }}</div>
    </div>
    <a href="{{ route('admin.team.index') }}" class="btn btn-secondary">← Back</a>
</div>

<form method="POST" action="{{ route('admin.team.update', $member) }}">
    @csrf @method('PATCH')
    <div style="max-width:640px;background:linear-gradient(135deg,rgba(255,255,255,0.9),rgba(255,255,255,0.6));backdrop-filter:blur(20px);-webkit-backdrop-filter:blur(20px);border:1px solid rgba(255,255,255,0.5);border-radius:var(--radius);padding:28px;box-shadow:0 10px 40px rgba(139,111,71,0.08);"><div>
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;">
            <div class="form-group">
                <label>Name</label>
                <input type="text" name="name" class="form-control" value="{{ old('name', $member->name) }}" required>
            </div>
            <div class="form-group">
                <label>Role</label>
                <input type="text" name="role" class="form-control" value="{{ old('role', $member->role) }}">
            </div>
        </div>
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;">
            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" class="form-control" value="{{ old('email', $member->email) }}">
            </div>
            <div class="form-group">
                <label>Phone</label>
                <input type="text" name="phone" class="form-control" value="{{ old('phone', $member->phone) }}">
            </div>
        </div>
        <div class="form-group">
            <label>Bio</label>
            <textarea name="bio" class="form-control" rows="4">{{ old('bio', $member->bio) }}</textarea>
        </div>
        <div class="form-group">
            <label>Sort Order</label>
            <input type="number" name="sort_order" class="form-control" value="{{ old('sort_order', $member->sort_order) }}" style="width:120px;">
        </div>
        <div class="form-check" style="margin-bottom:20px;">
            <input type="hidden" name="is_active" value="0">
            <input type="checkbox" name="is_active" value="1" {{ old('is_active', $member->is_active) ? 'checked' : '' }}>
            <label>Active</label>
        </div>
        <button type="submit" class="btn btn-primary">Save Changes</button>
    </div></div>
</form>
@endsection
