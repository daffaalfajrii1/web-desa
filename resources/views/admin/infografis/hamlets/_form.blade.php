<div class="form-group">
    <label>Nama Dusun <span class="text-danger">*</span></label>
    <input type="text" name="name" required class="form-control @error('name') is-invalid @enderror"
           value="{{ old('name', $hamlet->name ?? '') }}">
    @error('name')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="form-group">
    <label>Urutan</label>
    <input type="number" name="sort_order" class="form-control @error('sort_order') is-invalid @enderror"
           value="{{ old('sort_order', $hamlet->sort_order ?? 0) }}">
    @error('sort_order')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="form-check">
    <input type="checkbox" name="is_active" value="1" class="form-check-input" id="is_active"
        {{ old('is_active', $hamlet->is_active ?? true) ? 'checked' : '' }}>
    <label for="is_active" class="form-check-label">Aktif</label>
</div>