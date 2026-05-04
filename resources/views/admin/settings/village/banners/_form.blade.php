@php
    $isEdit = $banner->exists;
@endphp

<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            <label>Judul Banner</label>
            <input type="text" name="title" class="form-control @error('title') is-invalid @enderror" value="{{ old('title', $banner->title) }}">
            @error('title')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group">
            <label>Subjudul</label>
            <input type="text" name="subtitle" class="form-control @error('subtitle') is-invalid @enderror" value="{{ old('subtitle', $banner->subtitle) }}">
            @error('subtitle')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
    </div>

    <div class="col-md-4">
        <div class="form-group">
            <label>Gambar Banner {{ $isEdit ? '' : '*' }}</label>
            <input type="file" name="image" id="banner_image" class="form-control-file @error('image') is-invalid @enderror" accept="image/jpeg,image/png,image/webp" {{ $isEdit ? '' : 'required' }}>
            @error('image')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
            <small class="text-muted d-block mt-2">
                Rekomendasi ukuran: 1600x600 px, rasio landscape. Format JPG, PNG, atau WebP. Maksimal 4 MB.
            </small>
        </div>
    </div>

    <div class="col-md-4">
        <div class="form-group">
            <label>Urutan</label>
            <input type="number" name="sort_order" min="0" class="form-control @error('sort_order') is-invalid @enderror" value="{{ old('sort_order', $banner->sort_order ?? 0) }}">
            @error('sort_order')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
    </div>

    <div class="col-md-4">
        <label>Status</label>
        <div class="custom-control custom-switch mt-2">
            <input type="checkbox" name="is_active" value="1" class="custom-control-input" id="is_active" {{ old('is_active', $banner->is_active ?? true) ? 'checked' : '' }}>
            <label class="custom-control-label" for="is_active">Banner aktif</label>
        </div>
    </div>

    <div class="col-md-12">
        <label>Preview Banner</label>
        <div class="border rounded bg-light overflow-hidden" style="min-height: 220px;">
            @if($isEdit && $banner->image_path)
                <img id="banner_preview" src="{{ $banner->image_url }}" alt="{{ $banner->title ?: 'Banner Desa' }}" style="width:100%; max-height:360px; object-fit:cover;">
            @else
                <div id="banner_preview_empty" class="text-muted text-center py-5">
                    <i class="fas fa-image fa-3x mb-3"></i>
                    <div>Preview gambar banner akan tampil di sini.</div>
                </div>
                <img id="banner_preview" src="" alt="Preview banner" style="display:none; width:100%; max-height:360px; object-fit:cover;">
            @endif
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const input = document.getElementById('banner_image');
        const preview = document.getElementById('banner_preview');
        const empty = document.getElementById('banner_preview_empty');

        input.addEventListener('change', function () {
            const file = input.files && input.files[0];

            if (!file || !file.type.startsWith('image/')) {
                return;
            }

            preview.src = URL.createObjectURL(file);
            preview.style.display = 'block';

            if (empty) {
                empty.style.display = 'none';
            }
        });
    });
</script>
@endpush
