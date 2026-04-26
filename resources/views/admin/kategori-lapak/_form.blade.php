<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            <label>Nama Kategori <span class="text-danger">*</span></label>
            <input type="text" name="name" required class="form-control @error('name') is-invalid @enderror"
                   value="{{ old('name', $item->name ?? '') }}">
            @error('name')
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
        <div class="form-check">
            <input type="checkbox" name="is_active" value="1" class="form-check-input" id="is_active"
                {{ old('is_active', $item->is_active ?? true) ? 'checked' : '' }}>
            <label for="is_active" class="form-check-label">Kategori aktif</label>
        </div>
    </div>
</div>