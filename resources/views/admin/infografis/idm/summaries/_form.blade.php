<div class="row">
    <div class="col-md-4">
        <div class="form-group">
            <label>Tahun <span class="text-danger">*</span></label>
            <input type="text" name="year" class="form-control" value="{{ old('year', $item->year ?? date('Y')) }}" required>
        </div>
    </div>

    <div class="col-md-4">
        <div class="form-group">
            <label>Skor IKS <span class="text-danger">*</span></label>
            <input type="number" step="0.0001" min="0" max="1" name="iks_score" class="form-control" value="{{ old('iks_score', $item->iks_score ?? 0) }}" required>
        </div>
    </div>

    <div class="col-md-4">
        <div class="form-group">
            <label>Skor IKE <span class="text-danger">*</span></label>
            <input type="number" step="0.0001" min="0" max="1" name="ike_score" class="form-control" value="{{ old('ike_score', $item->ike_score ?? 0) }}" required>
        </div>
    </div>

    <div class="col-md-4">
        <div class="form-group">
            <label>Skor IKL <span class="text-danger">*</span></label>
            <input type="number" step="0.0001" min="0" max="1" name="ikl_score" class="form-control" value="{{ old('ikl_score', $item->ikl_score ?? 0) }}" required>
        </div>
    </div>

    <div class="col-md-8">
        <div class="form-group">
            <label>Keterangan</label>
            <textarea name="description" class="form-control" rows="4">{{ old('description', $item->description ?? '') }}</textarea>
        </div>
    </div>

    <div class="col-md-12">
        <div class="form-check mt-2">
            <input type="checkbox" name="is_active" value="1" class="form-check-input" id="is_active"
                {{ old('is_active', $item->is_active ?? true) ? 'checked' : '' }}>
            <label class="form-check-label" for="is_active">Data aktif</label>
        </div>
    </div>
</div>