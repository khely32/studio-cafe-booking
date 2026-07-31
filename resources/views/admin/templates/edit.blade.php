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
        <div style="background:linear-gradient(135deg,rgba(255,255,255,0.9),rgba(255,255,255,0.6));backdrop-filter:blur(20px);-webkit-backdrop-filter:blur(20px);border:1px solid rgba(255,255,255,0.5);border-radius:var(--radius);padding:28px;box-shadow:0 10px 40px rgba(139,111,71,0.08);"><div>
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
        <div style="background:linear-gradient(135deg,rgba(255,255,255,0.9),rgba(255,255,255,0.6));backdrop-filter:blur(20px);-webkit-backdrop-filter:blur(20px);border:1px solid rgba(255,255,255,0.5);border-radius:var(--radius);padding:28px;box-shadow:0 10px 40px rgba(139,111,71,0.08);"><div>
            <h2 style="font-size:14px;font-weight:600;margin-bottom:16px;font-family:Poppins,sans-serif;">Settings</h2>
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
