<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            <label>Nama Jabatan <span class="text-danger">*</span></label>
            <input type="text" name="name" required class="form-control @error('name') is-invalid @enderror"
                   value="{{ old('name', $item->name ?? '') }}"
                   placeholder="Contoh: Sekretaris Desa">
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
            <small class="text-muted">Kosongkan bila ingin otomatis.</small>
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group">
            <label>Jenis Jabatan</label>
            <input type="text" name="position_type" class="form-control @error('position_type') is-invalid @enderror"
                   value="{{ old('position_type', $item->position_type ?? '') }}"
                   placeholder="Contoh: kepala_desa / kasi / kaur / staf">
            @error('position_type')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <div class="col-md-3">
        <div class="form-group">
            <label>Urutan</label>
            <input type="number" name="sort_order" min="0" class="form-control @error('sort_order') is-invalid @enderror"
                   value="{{ old('sort_order', $item->sort_order ?? 0) }}">
            @error('sort_order')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <div class="col-md-3">
        <div class="form-group">
            <label>Status</label>
            <div class="form-check mt-2">
                <input type="checkbox" name="is_active" value="1" class="form-check-input" id="is_active"
                    {{ old('is_active', $item->is_active ?? true) ? 'checked' : '' }}>
                <label for="is_active" class="form-check-label">Aktif</label>
            </div>
        </div>
    </div>
</div>
