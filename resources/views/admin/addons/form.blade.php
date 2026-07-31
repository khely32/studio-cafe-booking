@extends('admin.layout')
@section('title', isset($addon) ? 'Edit Add-On' : 'New Add-On')

@section('content')
<div class="page-header">
    <div>
        <div class="page-title">{{ isset($addon) ? 'Edit Add-On' : 'New Add-On' }}</div>
        <div class="page-subtitle">{{ isset($addon) ? 'Update the add-on details below.' : 'Fill in the details for the new add-on.' }}</div>
    </div>
</div>

<div style="max-width:720px;background:linear-gradient(135deg,rgba(255,255,255,0.9),rgba(255,255,255,0.6));backdrop-filter:blur(20px);-webkit-backdrop-filter:blur(20px);border:1px solid rgba(255,255,255,0.5);border-radius:var(--radius);padding:28px;box-shadow:0 10px 40px rgba(139,111,71,0.08);">
    <div>
        <form method="POST" action="{{ isset($addon) ? route('admin.addons.update', $addon) : route('admin.addons.store') }}">
            @csrf
            @if(isset($addon)) @method('PUT') @endif

            <div class="form-group">
                <label>Name *</label>
                <input type="text" name="name" value="{{ old('name', $addon->name ?? '') }}" class="form-control" required placeholder="e.g. Adult (7 y/o up), 4R, Additional Backdrop">
            </div>

            <div class="form-group">
                <label>Category</label>
                <input type="text" name="category" value="{{ old('category', $addon->category ?? '') }}" class="form-control" placeholder="e.g. Extra person, Pets, Printed copy (leave blank for a standalone item)">
            </div>

            <div class="form-group">
                <label>Description / Note</label>
                <input type="text" name="description" value="{{ old('description', $addon->description ?? '') }}" class="form-control" placeholder="e.g. Beige, tropical green, Chocolate brown, black and White">
            </div>

            <div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;">
                <div class="form-group">
                    <label>Price (₱) *</label>
                    <input type="number" name="price" value="{{ old('price', $addon->price ?? 0) }}" class="form-control" required min="0" step="0.01" placeholder="0.00">
                    <div style="font-size:12px;color:var(--gray-500);margin-top:4px;">Enter <strong>0</strong> to display as FREE.</div>
                </div>
                <div class="form-group">
                    <label>Sort Order</label>
                    <input type="number" name="sort_order" value="{{ old('sort_order', $addon->sort_order ?? 0) }}" class="form-control" min="0">
                    <div style="font-size:12px;color:var(--gray-500);margin-top:4px;">Lower numbers appear first.</div>
                </div>
            </div>

            <div class="form-group">
                <div class="form-check">
                    <input type="checkbox" name="is_active" value="1" {{ old('is_active', $addon->is_active ?? true) ? 'checked' : '' }} id="is_active">
                    <label for="is_active">Active (visible on the website)</label>
                </div>
            </div>

            <div style="display:flex;gap:12px;margin-top:24px;padding-top:24px;border-top:1px solid rgba(0,0,0,0.06);">
                <button type="submit" class="btn btn-primary">{{ isset($addon) ? 'Update Add-On' : 'Create Add-On' }}</button>
                <a href="{{ route('admin.addons.index') }}" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection
