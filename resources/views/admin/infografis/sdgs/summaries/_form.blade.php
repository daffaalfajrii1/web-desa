<div class="row">
    <div class="col-md-4">
        <div class="form-group">
            <label>Tahun <span class="text-danger">*</span></label>
            <input type="text" name="year" class="form-control @error('year') is-invalid @enderror"
                   value="{{ old('year', $item->year ?? date('Y')) }}" required>
            @error('year')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <div class="col-md-8">
        <div class="form-group">
            <label>Catatan Umum</label>
            <textarea name="notes" rows="4" class="form-control @error('notes') is-invalid @enderror">{{ old('notes', $item->notes ?? '') }}</textarea>
            @error('notes')
                <div class="invalid-feedback d-block">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <div class="col-md-12">
        <div class="form-check">
            <input type="checkbox" name="is_active" value="1" class="form-check-input" id="is_active"
                {{ old('is_active', $item->is_active ?? true) ? 'checked' : '' }}>
            <label class="form-check-label" for="is_active">Data aktif</label>
        </div>
    </div>
</div>