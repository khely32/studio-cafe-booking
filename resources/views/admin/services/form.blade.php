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
                <label>Package Photo</label>
                <input type="file" id="image-input" accept="image/*" class="form-control" style="padding:9px 12px;">
                <input type="hidden" name="image" id="image-data" value="{{ old('image', $service->image ?? '') }}">
                <div style="display:flex;align-items:center;gap:16px;margin-top:10px;">
                    <img id="image-preview" src="{{ isset($service) && $service->image ? $service->image : '' }}" alt="Preview" style="width:140px;height:96px;object-fit:cover;border-radius:10px;border:1px solid rgba(0,0,0,0.1);{{ isset($service) && $service->image ? '' : 'display:none;' }}">
                    <div style="font-size:12px;color:var(--gray-500);line-height:1.6;">
                        Choose a photo of the package. It is resized automatically.<br>
                        <button type="button" id="image-clear" class="btn btn-secondary btn-sm" style="{{ isset($service) && $service->image ? '' : 'display:none;' }} margin-top:8px;">Remove photo</button>
                    </div>
                </div>
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

<script>
(function () {
    const fileInput = document.getElementById('image-input');
    const dataField = document.getElementById('image-data');
    const preview = document.getElementById('image-preview');
    const clearBtn = document.getElementById('image-clear');

    async function loadImage(file) {
        if ('createImageBitmap' in window) {
            try { return await createImageBitmap(file, { imageOrientation: 'from-image' }); } catch (e) {}
        }
        const url = URL.createObjectURL(file);
        const img = new Image();
        await new Promise((resolve, reject) => { img.onload = resolve; img.onerror = reject; img.src = url; });
        URL.revokeObjectURL(url);
        return img;
    }

    fileInput.addEventListener('change', async function () {
        const file = this.files[0];
        if (!file) return;
        try {
            const img = await loadImage(file);
            const MAX = 900;
            let w = img.width, h = img.height;
            if (w > MAX) { h = Math.round(h * MAX / w); w = MAX; }
            const canvas = document.createElement('canvas');
            canvas.width = w; canvas.height = h;
            canvas.getContext('2d').drawImage(img, 0, 0, w, h);
            const dataUrl = canvas.toDataURL('image/jpeg', 0.82);
            dataField.value = dataUrl;
            preview.src = dataUrl;
            preview.style.display = 'block';
            clearBtn.style.display = 'inline-block';
        } catch (e) {
            alert('Could not read that image. Please try another file.');
            fileInput.value = '';
        }
    });

    clearBtn.addEventListener('click', function () {
        dataField.value = '';
        preview.style.display = 'none';
        clearBtn.style.display = 'none';
        fileInput.value = '';
    });
})();
</script>
@endsection
