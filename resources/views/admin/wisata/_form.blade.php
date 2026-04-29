<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            <label>Nama Wisata <span class="text-danger">*</span></label>
            <input type="text" name="title" required class="form-control @error('title') is-invalid @enderror"
                   value="{{ old('title', $item->title ?? '') }}">
            @error('title')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group">
            <label>Slug</label>
            <input type="text" name="slug" class="form-control @error('slug') is-invalid @enderror"
                   value="{{ old('slug', $item->slug ?? '') }}">
            @error('slug')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <div class="col-md-12">
        <div class="form-group">
            <label>Ringkasan</label>
            <textarea name="excerpt" rows="3" class="form-control @error('excerpt') is-invalid @enderror">{{ old('excerpt', $item->excerpt ?? '') }}</textarea>
            @error('excerpt')
                <div class="invalid-feedback d-block">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <div class="col-md-12">
        <div class="form-group">
            <label>Deskripsi</label>
            <textarea name="description" id="editor" class="form-control @error('description') is-invalid @enderror">{{ old('description', $item->description ?? '') }}</textarea>
            @error('description')
                <div class="invalid-feedback d-block">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <div class="col-md-12">
        <div class="form-group">
            <label>Fasilitas</label>
            <textarea name="facilities" rows="4" class="form-control @error('facilities') is-invalid @enderror" placeholder="Contoh: Parkir, Mushola, Toilet, Gazebo, Warung">{{ old('facilities', $item->facilities ?? '') }}</textarea>
            @error('facilities')
                <div class="invalid-feedback d-block">{{ $message }}</div>
            @enderror
            <small class="text-muted">Bisa dipisah koma atau per baris.</small>
        </div>
    </div>

    <div class="col-md-12">
        <div class="form-group">
            <label>Embed Peta</label>
            <textarea name="map_embed" rows="4" class="form-control @error('map_embed') is-invalid @enderror" placeholder="Tempel iframe embed Google Maps di sini">{{ old('map_embed', $item->map_embed ?? '') }}</textarea>
            @error('map_embed')
                <div class="invalid-feedback d-block">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <div class="col-md-4">
        <div class="form-group">
            <label>Hari Buka</label>
            <input type="text" name="open_days" class="form-control" value="{{ old('open_days', $item->open_days ?? '') }}" placeholder="Contoh: Senin - Minggu">
        </div>
    </div>

    <div class="col-md-4">
        <div class="form-group">
            <label>Hari Tutup</label>
            <input type="text" name="closed_days" class="form-control" value="{{ old('closed_days', $item->closed_days ?? '') }}" placeholder="Contoh: Tidak ada / Jumat">
        </div>
    </div>

    <div class="col-md-2">
        <div class="form-group">
            <label>Jam Buka</label>
            <input type="time" name="open_time" class="form-control" value="{{ old('open_time', $item->open_time ?? '') }}">
        </div>
    </div>

    <div class="col-md-2">
        <div class="form-group">
            <label>Jam Tutup</label>
            <input type="time" name="close_time" class="form-control" value="{{ old('close_time', $item->close_time ?? '') }}">
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group">
            <label>Alamat / Lokasi</label>
            <input type="text" name="address" class="form-control" value="{{ old('address', $item->address ?? '') }}">
        </div>
    </div>

    <div class="col-md-3">
        <div class="form-group">
            <label>Nama Kontak</label>
            <input type="text" name="contact_person" class="form-control" value="{{ old('contact_person', $item->contact_person ?? '') }}">
        </div>
    </div>

    <div class="col-md-3">
        <div class="form-group">
            <label>No. Kontak</label>
            <input type="text" name="contact_phone" class="form-control" value="{{ old('contact_phone', $item->contact_phone ?? '') }}">
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group">
            <label>Foto Utama</label>
            <input type="file" name="main_image" class="form-control-file @error('main_image') is-invalid @enderror">
            @error('main_image')
                <div class="text-danger small">{{ $message }}</div>
            @enderror

            @if(!empty($item?->main_image))
                <div class="mt-2">
                    <img src="{{ asset('storage/' . $item->main_image) }}" width="150" alt="foto utama">
                </div>
            @endif
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group">
            <label>Galeri Foto</label>
            <input type="file" name="gallery_images[]" multiple class="form-control-file @error('gallery_images.*') is-invalid @enderror">
            <small class="text-muted">Bisa upload banyak foto sekaligus.</small>
            @error('gallery_images.*')
                <div class="text-danger small">{{ $message }}</div>
            @enderror
        </div>
    </div>

    @if(!empty($item?->images) && $item->images->count())
        <div class="col-md-12">
            <div class="form-group">
                <label>Galeri Saat Ini</label>
                <div class="row">
                    @foreach($item->images as $image)
                        <div class="col-md-2 text-center mb-3">
                            <img src="{{ asset('storage/' . $image->image_path) }}" class="img-fluid mb-2" style="height:120px; object-fit:cover;">
                            <div class="form-check">
                                <input type="checkbox" name="delete_gallery_images[]" value="{{ $image->id }}" class="form-check-input" id="delete_image_{{ $image->id }}">
                                <label class="form-check-label" for="delete_image_{{ $image->id }}">
                                    Hapus
                                </label>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @endif

    <div class="col-md-6">
        <div class="form-check">
            <input type="checkbox" name="is_featured" value="1" class="form-check-input" id="is_featured"
                {{ old('is_featured', $item->is_featured ?? false) ? 'checked' : '' }}>
            <label for="is_featured" class="form-check-label">Wisata unggulan</label>
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-check">
            <input type="checkbox" name="is_active" value="1" class="form-check-input" id="is_active"
                {{ old('is_active', $item->is_active ?? true) ? 'checked' : '' }}>
            <label for="is_active" class="form-check-label">Wisata aktif</label>
        </div>
    </div>
</div>