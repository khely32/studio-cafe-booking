@extends('admin.layout')
@section('title', 'Edit: {{ $template->title }}')

@section('content')
<div class="page-header">
    <div>
        <div class="page-title">Edit Template</div>
        <div class="page-subtitle">{{ $template->title }}</div>
    </div>
    <a href="{{ route('admin.templates.index') }}" class="btn btn-secondary">← Back</a>
</div>

<form method="POST" action="{{ route('admin.templates.update', $template) }}">
    @csrf @method('PATCH')
    <div style="display:grid;grid-template-columns:2fr 1fr;gap:24px;">
        <div class="card"><div class="card-body">
            <div class="form-group">
                <label>Title</label>
                <input type="text" name="title" class="form-control" value="{{ old('title', $template->title) }}" required>
            </div>
            <div class="form-group">
                <label>Subject</label>
                <input type="text" name="subject" class="form-control" value="{{ old('subject', $template->subject) }}">
            </div>
            <div class="form-group">
                <label>Body Content</label>
                <textarea name="body" class="form-control" rows="18">{{ old('body', $template->body) }}</textarea>
            </div>
        </div></div>
        <div class="card"><div class="card-body">
            <h2 style="font-size:14px;font-weight:600;margin-bottom:16px;">Settings</h2>
            <div class="form-group">
                <label>Type</label>
                <select name="type" class="form-control">
                    @foreach(['email','sms','notification'] as $type)
                    <option value="{{ $type }}" {{ old('type', $template->type)===$type?'selected':'' }}>{{ ucfirst($type) }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-check" style="margin-bottom:20px;">
                <input type="hidden" name="is_active" value="0">
                <input type="checkbox" name="is_active" value="1" {{ old('is_active', $template->is_active) ? 'checked' : '' }}>
                <label>Active</label>
            </div>
            <button type="submit" class="btn btn-primary" style="width:100%;">Save Changes</button>
        </div></div>
    </div>
</form>
@endsection
