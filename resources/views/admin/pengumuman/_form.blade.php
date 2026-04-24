<div class="row">
    <div class="col-md-8">
        <div class="form-group">
            <label>Judul Pengumuman <span class="text-danger">*</span></label>
            <input type="text" name="title" required class="form-control @error('title') is-invalid @enderror"
                value="{{ old('title', $item->title ?? '') }}">
            @error('title')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <div class="col-md-4">
        <div class="form-group">
            <label>Slug</label>
            <input type="text" name="slug" id="slug" class="form-control @error('slug') is-invalid @enderror"
                value="{{ old('slug', $item->slug ?? '') }}">
            @error('slug')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
            <small class="text-muted">Kosongkan bila ingin otomatis.</small>
        </div>
    </div>

    <div class="col-md-12">
        <div class="form-group">
            <label>Ringkasan</label>
            <textarea name="excerpt" rows="4" class="form-control @error('excerpt') is-invalid @enderror">{{ old('excerpt', $item->excerpt ?? '') }}</textarea>
            @error('excerpt')
                <div class="invalid-feedback d-block">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <div class="col-md-12">
        <div class="form-group">
            <label>Konten</label>
            <textarea name="content" id="editor" class="form-control @error('content') is-invalid @enderror">{{ old('content', $item->content ?? '') }}</textarea>
            @error('content')
                <div class="invalid-feedback d-block">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group">
            <label>Featured Image</label>
            <input type="file" name="featured_image" class="form-control-file @error('featured_image') is-invalid @enderror">
            @error('featured_image')
                <div class="text-danger small">{{ $message }}</div>
            @enderror

            @if(!empty($item?->featured_image))
                <div class="mt-2">
                    <img src="{{ asset('storage/' . $item->featured_image) }}" alt="featured image" width="150">
                </div>
            @endif
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group">
            <label>Status <span class="text-danger">*</span></label>
            <select name="status" required class="form-control @error('status') is-invalid @enderror">
                <option value="draft" {{ old('status', $item->status ?? 'draft') === 'draft' ? 'selected' : '' }}>Draft</option>
                <option value="published" {{ old('status', $item->status ?? '') === 'published' ? 'selected' : '' }}>Published</option>
            </select>
            @error('status')
                <div class="invalid-feedback d-block">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <div class="col-md-12">
        <div class="form-group">
            <label>Lampiran PDF</label>
            <input type="file" name="attachment" accept="application/pdf" class="form-control-file @error('attachment') is-invalid @enderror">
            <small class="text-muted">Opsional. Format PDF maksimal 5 MB.</small>
            @error('attachment')
                <div class="text-danger small">{{ $message }}</div>
            @enderror

            @if(!empty($item?->attachment))
                <div class="mt-2">
                    <a href="{{ asset('storage/' . $item->attachment) }}" target="_blank" class="btn btn-danger btn-sm">
                        <i class="fas fa-file-pdf"></i> Lihat Lampiran Saat Ini
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>