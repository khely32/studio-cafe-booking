@extends('admin.layout')
@section('title', isset($service) ? 'Edit Service' : 'New Service')

@section('content')
<div class="page-header">
    <div>
        <div class="page-title">{{ isset($service) ? 'Edit Service' : 'New Service' }}</div>
        <div class="page-subtitle">{{ isset($service) ? 'Update the package details below.' : 'Fill in the details for the new package.' }}</div>
    </div>
</div>

<div style="max-width:720px;background:linear-gradient(135deg,rgba(255,255,255,0.9),rgba(255,255,255,0.6));backdrop-filter:blur(20px);-webkit-backdrop-filter:blur(20px);border:1px solid rgba(255,255,255,0.5);border-radius:var(--radius);padding:28px;box-shadow:0 10px 40px rgba(139,111,71,0.08);">
    <div>
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
                <label>Image URL</label>
                <input type="url" name="image" value="{{ old('image', $service->image ?? '') }}" class="form-control" placeholder="https://images.unsplash.com/photo-...">
                @if(isset($service) && $service->image)
                <div style="margin-top:8px;"><img src="{{ $service->image }}" alt="" style="width:100px;height:70px;object-fit:cover;border-radius:8px;border:1px solid var(--gray-200);"></div>
                @endif
            </div>

            <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:20px;">
                <div class="form-group">
                    <label>Price (₱) *</label>
                    <input type="number" name="price" value="{{ old('price', $service->price ?? '') }}" class="form-control" required min="0" step="0.01" placeholder="0.00">
                </div>
                <div class="form-group">
                    <label>Duration (minutes) *</label>
                    <input type="number" name="duration_minutes" value="{{ old('duration_minutes', $service->duration_minutes ?? '') }}" class="form-control" required min="1" placeholder="30">
                </div>
                <div class="form-group">
                    <label>Max Pax *</label>
                    <input type="number" name="max_pax" value="{{ old('max_pax', $service->max_pax ?? '') }}" class="form-control" required min="1" placeholder="4">
                </div>
            </div>

            <div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;">
                <div class="form-group">
                    <label>Sort Order</label>
                    <input type="number" name="sort_order" value="{{ old('sort_order', $service->sort_order ?? 0) }}" class="form-control" min="0">
                </div>
                <div class="form-group">
                    <label>&nbsp;</label>
                    <div class="form-check">
                        <input type="checkbox" name="is_active" value="1" {{ old('is_active', $service->is_active ?? true) ? 'checked' : '' }} id="is_active">
                        <label for="is_active">Active (visible on booking page)</label>
                    </div>
                </div>
            </div>

            <div style="display:flex;gap:12px;margin-top:24px;padding-top:24px;border-top:1px solid rgba(0,0,0,0.06);">
                <button type="submit" class="btn btn-primary">{{ isset($service) ? 'Update Service' : 'Create Service' }}</button>
                <a href="{{ route('admin.services.index') }}" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection
