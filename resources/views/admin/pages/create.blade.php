@extends('admin.layout')
@section('title', 'Create Page')

@section('content')
<div class="page-header">
    <div>
        <div class="page-title">Create Page</div>
        <div class="page-subtitle">Add a new content page</div>
    </div>
    <a href="{{ route('admin.pages.index') }}" class="btn btn-secondary">← Back</a>
</div>

<form method="POST" action="{{ route('admin.pages.store') }}">
    @csrf
    <div style="display:grid;grid-template-columns:2fr 1fr;gap:24px;">
        <div class="card"><div class="card-body">
            <div class="form-group">
                <label>Title</label>
                <input type="text" name="title" class="form-control" value="{{ old('title') }}" required>
            </div>
            <div class="form-group">
                <label>Slug</label>
                <input type="text" name="slug" class="form-control" value="{{ old('slug') }}" placeholder="auto-generated from title" pattern="[a-z0-9\-]*">
            </div>
            <div class="form-group">
                <label>Content</label>
                <textarea name="content" class="form-control" rows="16">{{ old('content') }}</textarea>
            </div>
        </div></div>
        <div class="card"><div class="card-body">
            <h2 style="font-size:14px;font-weight:600;margin-bottom:16px;">Settings</h2>
            <div class="form-group">
                <label>Meta Title</label>
                <input type="text" name="meta_title" class="form-control" value="{{ old('meta_title') }}">
            </div>
            <div class="form-group">
                <label>Meta Description</label>
                <textarea name="meta_description" class="form-control" rows="3">{{ old('meta_description') }}</textarea>
            </div>
            <div class="form-check" style="margin-bottom:20px;">
                <input type="hidden" name="is_published" value="0">
                <input type="checkbox" name="is_published" value="1" {{ old('is_published', 1) ? 'checked' : '' }}>
                <label>Published</label>
            </div>
            <button type="submit" class="btn btn-primary" style="width:100%;">Create Page</button>
        </div></div>
    </div>
</form>
@endsection
