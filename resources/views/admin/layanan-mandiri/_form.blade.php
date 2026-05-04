@php
    $displayCode = $item?->service_code ?? ($nextCode ?? 'Otomatis');
@endphp

<div class="row">
    <div class="col-md-4">
        <div class="form-group">
            <label>Kode Layanan</label>
            <input type="text" class="form-control" value="{{ $displayCode }}" readonly>
            <small class="text-muted">Kode dibuat otomatis oleh sistem.</small>
        </div>
    </div>

    <div class="col-md-8">
        <div class="form-group">
            <label>Nama Layanan <span class="text-danger">*</span></label>
            <input type="text" name="service_name" class="form-control @error('service_name') is-invalid @enderror"
                   value="{{ old('service_name', $item->service_name ?? '') }}" required>
            @error('service_name')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <div class="col-md-12">
        <div class="form-group">
            <label>Deskripsi Layanan</label>
            <textarea name="description" rows="4" class="form-control @error('description') is-invalid @enderror" placeholder="Jelaskan fungsi layanan dan informasi singkat untuk pemohon.">{{ old('description', $item->description ?? '') }}</textarea>
            @error('description')
                <div class="invalid-feedback d-block">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <div class="col-md-12">
        <div class="form-group">
            <label>Syarat Layanan</label>
            <textarea name="requirements" rows="5" class="form-control @error('requirements') is-invalid @enderror" placeholder="Contoh: Fotokopi KTP, Fotokopi KK, Surat pengantar RT/RW">{{ old('requirements', $item->requirements ?? '') }}</textarea>
            @error('requirements')
                <div class="invalid-feedback d-block">{{ $message }}</div>
            @enderror
            <small class="text-muted">Tulis satu syarat per baris agar mudah dibaca.</small>
        </div>
    </div>

    <div class="col-md-3">
        <div class="form-group">
            <label>Urutan</label>
            <input type="number" min="0" name="sort_order" class="form-control @error('sort_order') is-invalid @enderror"
                   value="{{ old('sort_order', $item->sort_order ?? 0) }}">
            @error('sort_order')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <div class="col-md-9 d-flex align-items-center">
        <div class="form-check mt-3">
            <input type="checkbox" name="is_active" value="1" class="form-check-input" id="is_active"
                {{ old('is_active', $item->is_active ?? true) ? 'checked' : '' }}>
            <label class="form-check-label" for="is_active">Layanan aktif</label>
        </div>
    </div>
</div>
