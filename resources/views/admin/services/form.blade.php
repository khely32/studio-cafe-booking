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
                    <div style="text-align:center;">
                        <img id="image-preview" src="{{ isset($service) && $service->image ? $service->image : '' }}" alt="Preview" style="width:110px;height:147px;object-fit:cover;border-radius:10px;border:1px solid rgba(0,0,0,0.1);{{ isset($service) && $service->image ? '' : 'display:none;' }}">
                        <button type="button" id="crop-current" class="btn btn-secondary btn-sm" style="{{ isset($service) && $service->image ? '' : 'display:none;' }} margin-top:6px;">Crop current photo</button>
                    </div>
                    <div style="font-size:12px;color:var(--gray-500);line-height:1.6;">
                        Choose a photo of the package. After selecting, you can crop it.<br>
                        Photos are always saved in <strong>portrait</strong> (3:4) format.<br>
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

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.2/cropper.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.2/cropper.min.js"></script>

<div id="crop-modal" style="display:none;position:fixed;inset:0;z-index:9999;background:rgba(0,0,0,0.6);backdrop-filter:blur(4px);-webkit-backdrop-filter:blur(4px);align-items:center;justify-content:center;">
    <div style="background:#fff;border-radius:16px;padding:20px;max-width:92vw;max-height:88vh;width:680px;display:flex;flex-direction:column;box-shadow:0 20px 60px rgba(0,0,0,0.3);">
        <div style="font-weight:600;margin-bottom:12px;color:#111;">Crop your photo</div>
        <div style="flex:1;min-height:0;overflow:auto;background:#111;border-radius:10px;">
            <img id="crop-image" alt="Crop" style="display:block;max-width:100%;">
        </div>
        <div style="display:flex;gap:10px;justify-content:flex-end;margin-top:14px;">
            <button type="button" id="crop-cancel" class="btn btn-secondary">Cancel</button>
            <button type="button" id="crop-apply" class="btn btn-primary">Apply Crop</button>
        </div>
    </div>
</div>

<script>
(function () {
    const fileInput = document.getElementById('image-input');
    const dataField = document.getElementById('image-data');
    const preview = document.getElementById('image-preview');
    const clearBtn = document.getElementById('image-clear');
    const cropBtn = document.getElementById('crop-current');
    const modal = document.getElementById('crop-modal');
    const cropImg = document.getElementById('crop-image');
    let cropper = null;
    let currentUrl = null;

    function setCropped(canvas) {
        const dataUrl = canvas.toDataURL('image/jpeg', 0.85);
        dataField.value = dataUrl;
        preview.src = dataUrl;
        preview.style.display = 'block';
        clearBtn.style.display = 'inline-block';
        cropBtn.style.display = 'inline-block';
    }

    function closeCrop() {
        if (cropper) { cropper.destroy(); cropper = null; }
        if (currentUrl) { URL.revokeObjectURL(currentUrl); currentUrl = null; }
        modal.style.display = 'none';
        document.body.style.overflow = '';
        fileInput.value = '';
    }

    function openCrop(src, isBlobUrl) {
        if (typeof Cropper === 'undefined') {
            if (isBlobUrl) {
                const img = new Image();
                img.onload = function () {
                    const MAX = 900;
                    const scale = Math.min(1, MAX / Math.max(img.width, img.height));
                    const sw = Math.round(img.width * scale);
                    const sh = Math.round(img.height * scale);
                    let cw, ch;
                    if (sw / sh > 3 / 4) { cw = Math.round(sh * 3 / 4); ch = sh; } else { cw = sw; ch = Math.round(sw * 4 / 3); }
                    const sx = Math.round((sw - cw) / 2), sy = Math.round((sh - ch) / 2);
                    const canvas = document.createElement('canvas');
                    canvas.width = cw; canvas.height = ch;
                    canvas.getContext('2d').drawImage(img, sx / scale, sy / scale, cw / scale, ch / scale, 0, 0, cw, ch);
                    setCropped(canvas);
                    URL.revokeObjectURL(src);
                };
                img.onerror = function () { URL.revokeObjectURL(src); alert('Could not read that image. Please try another file.'); };
                img.src = src;
            }
            return;
        }
        cropImg.onload = function () {
            modal.style.display = 'flex';
            document.body.style.overflow = 'hidden';
            cropper = new Cropper(cropImg, { viewMode: 1, aspectRatio: 3 / 4, autoCropArea: 0.9, background: false, checkOrientation: true });
        };
        cropImg.onerror = function () {
            if (currentUrl) { URL.revokeObjectURL(currentUrl); currentUrl = null; }
            alert('Could not read that image. Please try another file.');
        };
        cropImg.src = src;
    }

    fileInput.addEventListener('change', function () {
        const file = this.files[0];
        if (!file) return;
        const url = URL.createObjectURL(file);
        currentUrl = url;
        openCrop(url, true);
    });

    cropBtn.addEventListener('click', function () {
        const src = dataField.value;
        if (!src) return;
        currentUrl = null;
        openCrop(src, false);
    });

    document.getElementById('crop-apply').addEventListener('click', function () {
        if (!cropper) return;
        const canvas = cropper.getCroppedCanvas({ maxWidth: 900, maxHeight: 900 });
        setCropped(canvas);
        closeCrop();
    });

    document.getElementById('crop-cancel').addEventListener('click', closeCrop);
    modal.addEventListener('click', function (e) { if (e.target === modal) closeCrop(); });

    clearBtn.addEventListener('click', function () {
        dataField.value = '';
        preview.style.display = 'none';
        clearBtn.style.display = 'none';
        cropBtn.style.display = 'none';
        fileInput.value = '';
    });
})();
</script>
@endsection
