@extends('admin.layout')
@section('title', 'Create Poll')

@section('content')
<div class="page-header">
    <div>
        <div class="page-title">Create Poll</div>
        <div class="page-subtitle">Create a new poll for your audience</div>
    </div>
    <a href="{{ route('admin.polls.index') }}" class="btn btn-secondary">← Back</a>
</div>

<form method="POST" action="{{ route('admin.polls.store') }}">
    @csrf
    <div style="display:grid;grid-template-columns:2fr 1fr;gap:24px;">
        <div class="card"><div class="card-body">
            <div class="form-group">
                <label>Question</label>
                <input type="text" name="question" class="form-control" value="{{ old('question') }}" required placeholder="e.g. Which theme should we offer next?">
            </div>
            <div class="form-group">
                <label>Description (optional)</label>
                <textarea name="description" class="form-control" rows="3">{{ old('description') }}</textarea>
            </div>
            <div id="options-container">
                <label style="font-size:13px;font-weight:500;margin-bottom:8px;display:block;">Options</label>
                <div style="display:flex;gap:8px;margin-bottom:8px;">
                    <input type="text" name="options[]" class="form-control" placeholder="Option 1" required>
                </div>
                <div style="display:flex;gap:8px;margin-bottom:8px;">
                    <input type="text" name="options[]" class="form-control" placeholder="Option 2" required>
                </div>
            </div>
            <button type="button" onclick="addOption()" class="btn btn-sm btn-secondary" style="margin-bottom:24px;">+ Add Option</button>
            <button type="submit" class="btn btn-primary" style="width:100%;">Create Poll</button>
        </div></div>
        <div class="card"><div class="card-body">
            <h2 style="font-size:14px;font-weight:600;margin-bottom:16px;">Settings</h2>
            <div class="form-check" style="margin-bottom:16px;">
                <input type="hidden" name="is_active" value="0">
                <input type="checkbox" name="is_active" value="1" {{ old('is_active', 1) ? 'checked' : '' }}>
                <label>Active</label>
            </div>
            <div class="form-check">
                <input type="hidden" name="allow_multiple" value="0">
                <input type="checkbox" name="allow_multiple" value="1" {{ old('allow_multiple') ? 'checked' : '' }}>
                <label>Allow multiple selections</label>
            </div>
        </div></div>
    </div>
</form>

<script>
let count = 2;
function addOption() {
    count++;
    const row = document.createElement('div');
    row.style.cssText = 'display:flex;gap:8px;margin-bottom:8px;';
    row.innerHTML = '<input type="text" name="options[]" class="form-control" placeholder="Option ' + count + '" required><button type="button" onclick="this.parentElement.remove()" class="btn btn-sm btn-danger">✕</button>';
    document.getElementById('options-container').appendChild(row);
}
</script>
@endsection
