@extends('admin.layout')
@section('title', 'Edit Poll')

@section('content')
<div class="page-header">
    <div>
        <div class="page-title">Edit Poll</div>
        <div class="page-subtitle">{{ $poll->question }}</div>
    </div>
    <a href="{{ route('admin.polls.index') }}" class="btn btn-secondary">← Back</a>
</div>

<form method="POST" action="{{ route('admin.polls.update', $poll) }}">
    @csrf @method('PATCH')
    <div style="display:grid;grid-template-columns:2fr 1fr;gap:24px;">
        <div class="card"><div class="card-body">
            <div class="form-group">
                <label>Question</label>
                <input type="text" name="question" class="form-control" value="{{ old('question', $poll->question) }}" required>
            </div>
            <div class="form-group">
                <label>Description</label>
                <textarea name="description" class="form-control" rows="3">{{ old('description', $poll->description) }}</textarea>
            </div>
            <div id="options-container">
                <label style="font-size:13px;font-weight:500;margin-bottom:8px;display:block;">Options</label>
                @foreach($poll->options as $opt)
                <div style="display:flex;gap:8px;margin-bottom:8px;">
                    <input type="text" name="options[]" class="form-control" value="{{ old('options[]', $opt->text) }}" required>
                    <input type="hidden" name="option_ids[]" value="{{ $opt->id }}">
                    <button type="button" onclick="this.parentElement.remove()" class="btn btn-sm btn-danger">✕</button>
                </div>
                @endforeach
            </div>
            <button type="button" onclick="addOption()" class="btn btn-sm btn-secondary" style="margin-bottom:24px;">+ Add Option</button>
            <button type="submit" class="btn btn-primary" style="width:100%;">Save Changes</button>
        </div></div>
        <div class="card"><div class="card-body">
            <h2 style="font-size:14px;font-weight:600;margin-bottom:16px;">Settings</h2>
            <div class="form-check" style="margin-bottom:16px;">
                <input type="hidden" name="is_active" value="0">
                <input type="checkbox" name="is_active" value="1" {{ old('is_active', $poll->is_active) ? 'checked' : '' }}>
                <label>Active</label>
            </div>
            <div class="form-check">
                <input type="hidden" name="allow_multiple" value="0">
                <input type="checkbox" name="allow_multiple" value="1" {{ old('allow_multiple', $poll->allow_multiple) ? 'checked' : '' }}>
                <label>Allow multiple selections</label>
            </div>
            <div style="margin-top:20px;padding-top:16px;border-top:1px solid var(--gray-100);">
                <div style="font-size:12px;color:var(--gray-500);margin-bottom:4px;">Total Votes</div>
                <div style="font-size:24px;font-weight:700;">{{ $poll->total_votes }}</div>
            </div>
        </div></div>
    </div>
</form>

<script>
function addOption() {
    const container = document.getElementById('options-container');
    const row = document.createElement('div');
    row.style.cssText = 'display:flex;gap:8px;margin-bottom:8px;';
    row.innerHTML = '<input type="text" name="options[]" class="form-control" placeholder="New option" required><button type="button" onclick="this.parentElement.remove()" class="btn btn-sm btn-danger">✕</button>';
    container.appendChild(row);
}
</script>
@endsection
