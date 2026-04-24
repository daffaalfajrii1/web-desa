<div class="row">
    <div class="col-md-8">
        <div class="form-group">
            <label>Judul Halaman</label>
            <input
                type="text"
                name="title"
                class="form-control @error('title') is-invalid @enderror"
                value="{{ old('title', $page->title ?? '') }}"
            >
            @error('title')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <div class="col-md-4">
        <div class="form-group">
            <label>Slug</label>
            <input
                type="text"
                name="slug"
                id="slug"
                class="form-control @error('slug') is-invalid @enderror"
                value="{{ old('slug', $page->slug ?? '') }}"
            >
            @error('slug')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
            <small class="text-muted">Kosongkan bila ingin otomatis.</small>
        </div>
    </div>

    <div class="col-md-12">
        <div class="form-group">
            <label>Ringkasan</label>
            <textarea
                name="excerpt"
                rows="4"
                class="form-control @error('excerpt') is-invalid @enderror"
            >{{ old('excerpt', $page->excerpt ?? '') }}</textarea>
            @error('excerpt')
                <div class="invalid-feedback d-block">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <div class="col-md-12">
    <div class="form-group">
        <label>Konten</label>
        <textarea
            name="content"
            id="editor"
            class="form-control @error('content') is-invalid @enderror"
        >{{ old('content', $page->content ?? '') }}</textarea>
        @error('content')
            <div class="invalid-feedback d-block">{{ $message }}</div>
        @enderror
    </div>
</div>

    <div class="col-md-6">
        <div class="form-group">
            <label>Featured Image</label>
            <input
                type="file"
                name="featured_image"
                class="form-control-file @error('featured_image') is-invalid @enderror"
            >
            @error('featured_image')
                <div class="text-danger small">{{ $message }}</div>
            @enderror

            @if(!empty($page?->featured_image))
                <div class="mt-2">
                    <img src="{{ asset('storage/' . $page->featured_image) }}" alt="featured image" width="150">
                </div>
            @endif
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group">
            <label>Status</label>
            <select name="status" class="form-control @error('status') is-invalid @enderror">
                <option value="draft" {{ old('status', $page->status ?? 'draft') === 'draft' ? 'selected' : '' }}>Draft</option>
                <option value="published" {{ old('status', $page->status ?? '') === 'published' ? 'selected' : '' }}>Published</option>
            </select>
            @error('status')
                <div class="invalid-feedback d-block">{{ $message }}</div>
            @enderror
        </div>
    </div>
</div>