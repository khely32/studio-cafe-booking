@extends('admin.layout')
@section('title', isset($service) ? 'Edit Service' : 'New Service')

@section('content')
<div class="page-header">
    <div>
        <div class="page-title">{{ isset($service) ? 'Edit Service' : 'New Service' }}</div>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <form method="POST" action="{{ isset($service) ? route('admin.services.update', $service) : route('admin.services.store') }}">
            @csrf
            @if(isset($service)) @method('PUT') @endif

            <div class="form-group">
                <label>Package Name *</label>
                <input type="text" name="name" value="{{ old('name', $service->name ?? '') }}" class="form-control" required placeholder="e.g. SELFIE, DUO, PARTY">
            </div>

            <div class="form-group">
                <label>Description</label>
                <textarea name="description" class="form-control" rows="8" placeholder="Enter package details, one per line...">{{ old('description', $service->description ?? '') }}</textarea>
            </div>

            <div class="form-group">
                <label>Image URL (optional)</label>
                <input type="url" name="image" value="{{ old('image', $service->image ?? '') }}" class="form-control" placeholder="https://images.unsplash.com/photo-...">
                @if(isset($service) && $service->image)
                <div style="margin-top:8px;"><img src="{{ $service->image }}" alt="" style="width:120px;height:80px;object-fit:cover;border-radius:8px;border:1px solid var(--gray-200);"></div>
                @endif
            </div>

            <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:16px;">
                <div class="form-group">
                    <label>Price (₱) *</label>
                    <input type="number" name="price" value="{{ old('price', $service->price ?? '') }}" class="form-control" required min="0" step="0.01">
                </div>
                <div class="form-group">
                    <label>Duration (minutes) *</label>
                    <input type="number" name="duration_minutes" value="{{ old('duration_minutes', $service->duration_minutes ?? '') }}" class="form-control" required min="1">
                </div>
                <div class="form-group">
                    <label>Max Pax *</label>
                    <input type="number" name="max_pax" value="{{ old('max_pax', $service->max_pax ?? '') }}" class="form-control" required min="1">
                </div>
            </div>

            <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;">
                <div class="form-group">
                    <label>Sort Order</label>
                    <input type="number" name="sort_order" value="{{ old('sort_order', $service->sort_order ?? 0) }}" class="form-control" min="0">
                </div>
                <div class="form-group">
                    <label>&nbsp;</label>
                    <div class="form-check">
                        <input type="checkbox" name="is_active" value="1" {{ old('is_active', $service->is_active ?? true) ? 'checked' : '' }}>
                        <label style="margin:0;">Active (visible on booking page)</label>
                    </div>
                </div>
            </div>

            <div style="display:flex;gap:10px;margin-top:8px;">
                <button type="submit" class="btn btn-primary">{{ isset($service) ? 'Update Service' : 'Create Service' }}</button>
                <a href="{{ route('admin.services.index') }}" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection
