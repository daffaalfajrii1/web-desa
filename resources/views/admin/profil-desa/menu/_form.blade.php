<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            <label>Judul Menu</label>
            <input type="text"
                   name="title"
                   class="form-control @error('title') is-invalid @enderror"
                   value="{{ old('title', $menu->title ?? '') }}">
            @error('title')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group">
            <label>Slug</label>
            <input type="text"
                   name="slug"
                   id="slug"
                   class="form-control @error('slug') is-invalid @enderror"
                   value="{{ old('slug', $menu->slug ?? '') }}">
            @error('slug')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
            <small class="text-muted">Kosongkan bila ingin otomatis.</small>
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group">
            <label>Hubungkan ke Halaman</label>
            <select name="page_id" class="form-control @error('page_id') is-invalid @enderror">
                <option value="">-- Pilih Halaman --</option>
                @foreach($pages as $pageItem)
                    <option value="{{ $pageItem->id }}"
                        {{ (string) old('page_id', $menu->page_id ?? '') === (string) $pageItem->id ? 'selected' : '' }}>
                        {{ $pageItem->title }}
                    </option>
                @endforeach
            </select>
            @error('page_id')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <div class="col-md-3">
        <div class="form-group">
            <label>Urutan</label>
            <input type="number"
                   name="sort_order"
                   class="form-control @error('sort_order') is-invalid @enderror"
                   value="{{ old('sort_order', $menu->sort_order ?? 0) }}">
            @error('sort_order')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <div class="col-md-3">
        <div class="form-group">
            <label>Status</label>
            <div class="form-check mt-2">
                <input type="checkbox"
                       name="is_active"
                       value="1"
                       class="form-check-input"
                       id="is_active"
                       {{ old('is_active', $menu->is_active ?? true) ? 'checked' : '' }}>
                <label class="form-check-label" for="is_active">Aktif</label>
            </div>
        </div>
    </div>
</div>