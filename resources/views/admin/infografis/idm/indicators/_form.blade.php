<div class="row">
    <div class="col-md-4">
        <div class="form-group">
            <label>Tahun IDM <span class="text-danger">*</span></label>
            <select name="idm_summary_id" class="form-control @error('idm_summary_id') is-invalid @enderror" required>
                <option value="">-- Pilih Tahun --</option>
                @forelse($summaries as $summary)
                    <option value="{{ $summary->id }}"
                        {{ (string) old('idm_summary_id', $item->idm_summary_id ?? '') === (string) $summary->id ? 'selected' : '' }}>
                        {{ $summary->year }}
                    </option>
                @empty
                    <option value="" disabled>Belum ada data ringkasan IDM</option>
                @endforelse
            </select>
            @error('idm_summary_id')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror

            @if(isset($summaries) && $summaries->count() === 0)
                <small class="text-danger">
                    Ringkasan IDM belum ada. Tambahkan dulu data di menu Ringkasan IDM.
                </small>
            @endif
        </div>
    </div>

    <div class="col-md-4">
        <div class="form-group">
            <label>Kategori <span class="text-danger">*</span></label>
            <select name="category" class="form-control @error('category') is-invalid @enderror" required>
                <option value="IKS" {{ old('category', $item->category ?? 'IKS') == 'IKS' ? 'selected' : '' }}>IKS</option>
                <option value="IKE" {{ old('category', $item->category ?? '') == 'IKE' ? 'selected' : '' }}>IKE</option>
                <option value="IKL" {{ old('category', $item->category ?? '') == 'IKL' ? 'selected' : '' }}>IKL</option>
            </select>
            @error('category')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <div class="col-md-4">
        <div class="form-group">
            <label>No Indikator <span class="text-danger">*</span></label>
            <input type="number" name="indicator_no" class="form-control @error('indicator_no') is-invalid @enderror"
                   value="{{ old('indicator_no', $item->indicator_no ?? 1) }}" required>
            @error('indicator_no')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group">
            <label>Nama Indikator <span class="text-danger">*</span></label>
            <input type="text" name="indicator_name" class="form-control @error('indicator_name') is-invalid @enderror"
                   value="{{ old('indicator_name', $item->indicator_name ?? '') }}" required>
            @error('indicator_name')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <div class="col-md-3">
        <div class="form-group">
            <label>Skor <span class="text-danger">*</span></label>
            <input type="number" name="score" min="0" max="5" class="form-control @error('score') is-invalid @enderror"
                   value="{{ old('score', $item->score ?? 0) }}" required>
            @error('score')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <div class="col-md-3">
        <div class="form-group">
            <label>Nilai+</label>
            <input type="number" step="0.0001" min="0" max="1" name="value" class="form-control @error('value') is-invalid @enderror"
                   value="{{ old('value', $item->value ?? 0) }}">
            @error('value')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group">
            <label>Keterangan</label>
            <textarea name="description" class="form-control @error('description') is-invalid @enderror" rows="3">{{ old('description', $item->description ?? '') }}</textarea>
            @error('description')
                <div class="invalid-feedback d-block">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group">
            <label>Kegiatan yang dapat dilakukan</label>
            <textarea name="activity" class="form-control @error('activity') is-invalid @enderror" rows="3">{{ old('activity', $item->activity ?? '') }}</textarea>
            @error('activity')
                <div class="invalid-feedback d-block">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <div class="col-md-2">
        <div class="form-group">
            <label>Pusat</label>
            <input type="text" name="executor_central" class="form-control" value="{{ old('executor_central', $item->executor_central ?? '') }}">
        </div>
    </div>

    <div class="col-md-2">
        <div class="form-group">
            <label>Provinsi</label>
            <input type="text" name="executor_province" class="form-control" value="{{ old('executor_province', $item->executor_province ?? '') }}">
        </div>
    </div>

    <div class="col-md-2">
        <div class="form-group">
            <label>Kabupaten</label>
            <input type="text" name="executor_regency" class="form-control" value="{{ old('executor_regency', $item->executor_regency ?? '') }}">
        </div>
    </div>

    <div class="col-md-2">
        <div class="form-group">
            <label>Desa</label>
            <input type="text" name="executor_village" class="form-control" value="{{ old('executor_village', $item->executor_village ?? '') }}">
        </div>
    </div>

    <div class="col-md-2">
        <div class="form-group">
            <label>CSR</label>
            <input type="text" name="executor_csr" class="form-control" value="{{ old('executor_csr', $item->executor_csr ?? '') }}">
        </div>
    </div>

    <div class="col-md-2">
        <div class="form-group">
            <label>Lainnya</label>
            <input type="text" name="executor_other" class="form-control" value="{{ old('executor_other', $item->executor_other ?? '') }}">
        </div>
    </div>

    <div class="col-md-3">
        <div class="form-group">
            <label>Urutan</label>
            <input type="number" name="sort_order" class="form-control" value="{{ old('sort_order', $item->sort_order ?? 0) }}">
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