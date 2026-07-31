@extends('admin.layout')
@section('title', 'Homepage Policy & Guides')

@section('content')
<style>
    .settings-editor { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; align-items: start; }
    .settings-panel {
        background: linear-gradient(135deg, rgba(255,255,255,0.9), rgba(255,255,255,0.6));
        backdrop-filter: blur(20px);
        -webkit-backdrop-filter: blur(20px);
        border: 1px solid rgba(255,255,255,0.5);
        border-radius: var(--radius);
        padding: 24px;
        box-shadow: 0 10px 40px rgba(139,111,71,0.08);
    }
    .settings-panel h2 { font-size: 16px; font-weight: 700; color: var(--gray-900); margin-bottom: 4px; }
    .settings-panel .sub { font-size: 12px; color: var(--gray-500); margin-bottom: 14px; }
    .settings-panel textarea.form-control { font-family: 'Courier New', monospace; font-size: 13px; line-height: 1.7; }
    .settings-hint {
        font-size: 12px; color: var(--gray-500); margin-top: 8px; line-height: 1.6;
    }
    .settings-hint code { background: rgba(139,111,71,0.1); padding: 1px 5px; border-radius: 4px; color: var(--gray-700); }
    .preview-box {
        margin-top: 14px; padding: 14px; background: #fff; border: 1px solid var(--gray-200);
        border-radius: var(--radius-sm); font-size: 13px; line-height: 1.7; color: var(--gray-800);
        display: none;
    }
    .preview-box strong { color: var(--gray-900); }
    @media (max-width: 900px) { .settings-editor { grid-template-columns: 1fr; } }
</style>

<div class="page-header">
    <div>
        <div class="page-title">Homepage Policy & Guides</div>
        <div class="page-subtitle">Edit the "Reminders and Payment Policy" and "Studio Guides" sections of your homepage.</div>
    </div>
</div>

<form method="POST" action="{{ route('admin.settings.homepage.update') }}">
    @csrf
    <div class="settings-editor">
        <div class="settings-panel">
            <h2>Reminders and Payment Policy</h2>
            <div class="sub">Shown under the "Reminders and Payment Policy" heading.</div>
            <textarea name="home_policy" class="form-control" rows="24" wrap="off">{{ $policy }}</textarea>
            <div class="settings-hint">
                Supports simple HTML: <code>&lt;strong&gt;...&lt;/strong&gt;</code> for bold,
                <code>&lt;br&gt;</code> for line breaks. Blank lines become paragraph gaps.
            </div>
        </div>
        <div class="settings-panel">
            <h2>Studio Guides</h2>
            <div class="sub">Shown under the "Studio Guides" heading.</div>
            <textarea name="home_guides" class="form-control" rows="24" wrap="off">{{ $guides }}</textarea>
            <div class="settings-hint">
                Supports simple HTML: <code>&lt;strong&gt;...&lt;/strong&gt;</code> for bold,
                <code>&lt;br&gt;</code> for line breaks.
            </div>
        </div>
    </div>
    <div style="display:flex;gap:12px;margin-top:20px;padding-top:20px;border-top:1px solid rgba(0,0,0,0.06);">
        <button type="submit" class="btn btn-primary">Save Changes</button>
        <button type="button" class="btn btn-secondary" onclick="togglePreview()">Preview</button>
        <a href="{{ route('home') }}" target="_blank" class="btn btn-ghost">View site</a>
    </div>
    <div id="preview-box" class="preview-box"></div>
</form>

<script>
function togglePreview() {
    const box = document.getElementById('preview-box');
    if (box.style.display === 'block') {
        box.style.display = 'none';
        return;
    }
    const policy = document.querySelector('[name=home_policy]').value;
    const guides = document.querySelector('[name=home_guides]').value;
    box.innerHTML = '<strong style="font-size:14px;">Reminders and Payment Policy</strong><br>' + policy + '<br><br><strong style="font-size:14px;">Studio Guides</strong><br>' + guides;
    box.style.display = 'block';
}
</script>
@endsection
