@extends('admin.layout')
@section('title', 'Add Team Member')

@section('content')
<div class="page-header">
    <div>
        <div class="page-title">Add Team Member</div>
        <div class="page-subtitle">Add a new member to your studio team</div>
    </div>
    <a href="{{ route('admin.team.index') }}" class="btn btn-secondary">← Back</a>
</div>

<form method="POST" action="{{ route('admin.team.store') }}">
    @csrf
    <div class="card" style="max-width:640px;"><div class="card-body">
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;">
            <div class="form-group">
                <label>Name</label>
                <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
            </div>
            <div class="form-group">
                <label>Role</label>
                <input type="text" name="role" class="form-control" value="{{ old('role') }}" placeholder="e.g. Studio Manager">
            </div>
        </div>
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;">
            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" class="form-control" value="{{ old('email') }}">
            </div>
            <div class="form-group">
                <label>Phone</label>
                <input type="text" name="phone" class="form-control" value="{{ old('phone') }}">
            </div>
        </div>
        <div class="form-group">
            <label>Bio</label>
            <textarea name="bio" class="form-control" rows="4">{{ old('bio') }}</textarea>
        </div>
        <div class="form-group">
            <label>Sort Order</label>
            <input type="number" name="sort_order" class="form-control" value="{{ old('sort_order', 0) }}" style="width:120px;">
        </div>
        <div class="form-check" style="margin-bottom:20px;">
            <input type="hidden" name="is_active" value="0">
            <input type="checkbox" name="is_active" value="1" {{ old('is_active', 1) ? 'checked' : '' }}>
            <label>Active</label>
        </div>
        <button type="submit" class="btn btn-primary">Add Member</button>
    </div></div>
</form>
@endsection
