<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            <label>Judul Produk Hukum <span class="text-danger">*</span></label>
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
            <input type="text" name="slug" id="slug" class="form-control @error('slug') is-invalid @enderror"
                value="{{ old('slug', $item->slug ?? '') }}">
            @error('slug')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
            <small class="text-muted">Kosongkan bila ingin otomatis.</small>
        </div>
    </div>

    <div class="col-md-3">
        <div class="form-group">
            <label>Kategori</label>
            <select name="category_id" class="form-control @error('category_id') is-invalid @enderror">
                <option value="">Tak Berkategori</option>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}"
                        {{ (string) old('category_id', $item->category_id ?? '') === (string) $category->id ? 'selected' : '' }}>
                        {{ $category->name }}
                    </option>
                @endforeach
            </select>
            @error('category_id')
                <div class="invalid-feedback d-block">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <div class="col-md-4">
        <div class="form-group">
            <label>Nomor Dokumen <span class="text-danger">*</span></label>
            <input type="text" name="number" required class="form-control @error('number') is-invalid @enderror"
                value="{{ old('number', $item->number ?? '') }}">
            @error('number')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <div class="col-md-4">
        <div class="form-group">
            <label>Jenis Produk Hukum <span class="text-danger">*</span></label>
            <input type="text" name="document_type" required class="form-control @error('document_type') is-invalid @enderror"
                value="{{ old('document_type', $item->document_type ?? '') }}"
                placeholder="Contoh: Perdes, SK, Perkades">
            @error('document_type')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <div class="col-md-4">
        <div class="form-group">
            <label>Tanggal Publikasi / Penetapan <span class="text-danger">*</span></label>
            <input type="date" name="published_date" required class="form-control @error('published_date') is-invalid @enderror"
                value="{{ old('published_date', isset($item->published_date) ? $item->published_date->format('Y-m-d') : '') }}">
            @error('published_date')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <div class="col-md-12">
        <div class="form-group">
            <label>Deskripsi</label>
            <textarea name="description" rows="5" class="form-control @error('description') is-invalid @enderror">{{ old('description', $item->description ?? '') }}</textarea>
            @error('description')
                <div class="invalid-feedback d-block">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group">
            <label>
                Upload File PDF
                @if(empty($item?->file_path))
                    <span class="text-danger">*</span>
                @endif
            </label>
            <input type="file"
                   name="file_path"
                   accept="application/pdf"
                   {{ empty($item?->file_path) ? 'required' : '' }}
                   class="form-control-file @error('file_path') is-invalid @enderror">
            <small class="text-muted">Format PDF, maksimal 5 MB.</small>
            @error('file_path')
                <div class="text-danger small">{{ $message }}</div>
            @enderror

            @if(!empty($item?->file_path))
                <div class="mt-2">
                    <a href="{{ asset('storage/' . $item->file_path) }}" target="_blank" class="btn btn-danger btn-sm">
                        <i class="fas fa-file-pdf"></i> Lihat PDF Saat Ini
                    </a>
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
</div>