<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            <label>Judul Section <span class="text-danger">*</span></label>
            <input type="text" name="title" required class="form-control @error('title') is-invalid @enderror" value="{{ old('title', $item->title ?? '') }}">
            @error('title') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
    </div>

    <div class="col-md-3">
        <div class="form-group">
            <label>Jenis Informasi <span class="text-danger">*</span></label>
            <select name="type" required class="form-control @error('type') is-invalid @enderror">
                <option value="berkala" {{ old('type', $item->type ?? '') === 'berkala' ? 'selected' : '' }}>Informasi Berkala</option>
                <option value="serta_merta" {{ old('type', $item->type ?? '') === 'serta_merta' ? 'selected' : '' }}>Informasi Serta Merta</option>
                <option value="setiap_saat" {{ old('type', $item->type ?? '') === 'setiap_saat' ? 'selected' : '' }}>Informasi Setiap Saat</option>
            </select>
            @error('type') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
    </div>

    <div class="col-md-3">
        <div class="form-group">
            <label>Urutan</label>
            <input type="number" name="sort_order" class="form-control @error('sort_order') is-invalid @enderror" value="{{ old('sort_order', $item->sort_order ?? 0) }}">
            @error('sort_order') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
    </div>

    <div class="col-md-12">
        <div class="form-check">
            <input type="checkbox" name="is_active" value="1" class="form-check-input" id="is_active"
                {{ old('is_active', $item->is_active ?? true) ? 'checked' : '' }}>
            <label for="is_active" class="form-check-label">Aktif</label>
        </div>
    </div>
</div>