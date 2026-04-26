<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            <label>Judul Produk <span class="text-danger">*</span></label>
            <input type="text" name="title" required class="form-control @error('title') is-invalid @enderror"
                   value="{{ old('title', $item->title ?? '') }}">
            @error('title')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <div class="col-md-3">
        <div class="form-group">
            <label>Slug</label>
            <input type="text" name="slug" class="form-control @error('slug') is-invalid @enderror"
                   value="{{ old('slug', $item->slug ?? '') }}">
            @error('slug')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <div class="col-md-3">
        <div class="form-group">
            <label>Kategori</label>
            <select name="shop_category_id" class="form-control @error('shop_category_id') is-invalid @enderror">
                <option value="">-- Pilih Kategori --</option>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}"
                        {{ (string) old('shop_category_id', $item->shop_category_id ?? '') === (string) $category->id ? 'selected' : '' }}>
                        {{ $category->name }}
                    </option>
                @endforeach
            </select>
            @error('shop_category_id')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <div class="col-md-4">
        <div class="form-group">
            <label>Harga <span class="text-danger">*</span></label>
            <input type="number" step="0.01" min="0" name="price" required class="form-control @error('price') is-invalid @enderror"
                   value="{{ old('price', $item->price ?? 0) }}">
            @error('price')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <div class="col-md-4">
        <div class="form-group">
            <label>Stok</label>
            <input type="number" min="0" name="stock" class="form-control @error('stock') is-invalid @enderror"
                   value="{{ old('stock', $item->stock ?? '') }}">
            @error('stock')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <div class="col-md-4">
        <div class="form-group">
            <label>Status <span class="text-danger">*</span></label>
            <select name="status" required class="form-control @error('status') is-invalid @enderror">
                <option value="available" {{ old('status', $item->status ?? 'available') === 'available' ? 'selected' : '' }}>Tersedia</option>
                <option value="out_of_stock" {{ old('status', $item->status ?? '') === 'out_of_stock' ? 'selected' : '' }}>Habis</option>
            </select>
            @error('status')
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

    <div class="col-md-4">
        <div class="form-group">
            <label>Nama Penjual</label>
            <input type="text" name="seller_name" class="form-control" value="{{ old('seller_name', $item->seller_name ?? '') }}">
        </div>
    </div>

    <div class="col-md-4">
        <div class="form-group">
            <label>WhatsApp</label>
            <input type="text" name="whatsapp" class="form-control" value="{{ old('whatsapp', $item->whatsapp ?? '') }}" placeholder="08xxxxxxxxxx">
        </div>
    </div>

    <div class="col-md-4">
        <div class="form-group">
            <label>Lokasi</label>
            <input type="text" name="location" class="form-control" value="{{ old('location', $item->location ?? '') }}">
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
                    <img src="{{ asset('storage/' . $item->main_image) }}" width="150">
                </div>
            @endif
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group">
            <label>Galeri Foto Produk</label>
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
            <label for="is_featured" class="form-check-label">Produk unggulan</label>
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-check">
            <input type="checkbox" name="is_active" value="1" class="form-check-input" id="is_active"
                {{ old('is_active', $item->is_active ?? true) ? 'checked' : '' }}>
            <label for="is_active" class="form-check-label">Produk aktif</label>
        </div>
    </div>
</div>