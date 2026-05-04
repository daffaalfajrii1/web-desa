@php
    $selectedType = old('field_type', $item->field_type ?? 'text');
    $optionsText = old('options_text', isset($item) && $item?->options ? implode(PHP_EOL, $item->options) : '');
@endphp

<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            <label>Label Field <span class="text-danger">*</span></label>
            <input type="text" name="field_label" class="form-control @error('field_label') is-invalid @enderror"
                   value="{{ old('field_label', $item->field_label ?? '') }}" placeholder="Contoh: Nama Pemohon" required>
            @error('field_label')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group">
            <label>Nama Field</label>
            <input type="text" name="field_name" class="form-control @error('field_name') is-invalid @enderror"
                   value="{{ old('field_name', $item->field_name ?? '') }}" placeholder="nama_pemohon">
            @error('field_name')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
            <small class="text-muted">Kosongkan untuk dibuat otomatis dari label. Gunakan huruf kecil, angka, dan underscore.</small>
        </div>
    </div>

    <div class="col-md-4">
        <div class="form-group">
            <label>Tipe Field <span class="text-danger">*</span></label>
            <select name="field_type" id="field_type" class="form-control @error('field_type') is-invalid @enderror" required>
                @foreach($fieldTypes as $value => $label)
                    <option value="{{ $value }}" {{ $selectedType === $value ? 'selected' : '' }}>
                        {{ $label }}
                    </option>
                @endforeach
            </select>
            @error('field_type')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <div class="col-md-4">
        <div class="form-group">
            <label>Placeholder</label>
            <input type="text" name="placeholder" class="form-control @error('placeholder') is-invalid @enderror"
                   value="{{ old('placeholder', $item->placeholder ?? '') }}" placeholder="Contoh: Masukkan nama lengkap">
            @error('placeholder')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <div class="col-md-2">
        <div class="form-group">
            <label>Urutan</label>
            <input type="number" min="0" name="sort_order" class="form-control @error('sort_order') is-invalid @enderror"
                   value="{{ old('sort_order', $item->sort_order ?? 0) }}">
            @error('sort_order')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <div class="col-md-2 d-flex align-items-center">
        <div class="form-check mt-3">
            <input type="checkbox" name="is_required" value="1" class="form-check-input" id="is_required"
                {{ old('is_required', $item->is_required ?? false) ? 'checked' : '' }}>
            <label class="form-check-label" for="is_required">Required</label>
        </div>
    </div>

    <div class="col-md-12">
        <div class="form-group">
            <label>Help Text</label>
            <textarea name="help_text" rows="3" class="form-control @error('help_text') is-invalid @enderror" placeholder="Petunjuk singkat untuk pengisi form.">{{ old('help_text', $item->help_text ?? '') }}</textarea>
            @error('help_text')
                <div class="invalid-feedback d-block">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <div class="col-md-12" id="options_box">
        <div class="form-group">
            <label>Opsi Pilihan</label>
            <textarea name="options_text" rows="5" class="form-control @error('options_text') is-invalid @enderror" placeholder="Tulis satu opsi per baris">{{ $optionsText }}</textarea>
            @error('options_text')
                <div class="invalid-feedback d-block">{{ $message }}</div>
            @enderror
            <small class="text-muted">Wajib untuk tipe select, radio, dan checkbox.</small>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function toggleOptionsBox() {
        const type = document.getElementById('field_type')?.value;
        const box = document.getElementById('options_box');
        if (!box) return;

        box.style.display = ['select', 'radio', 'checkbox'].includes(type) ? '' : 'none';
    }

    document.addEventListener('DOMContentLoaded', function () {
        const fieldType = document.getElementById('field_type');
        if (fieldType) {
            fieldType.addEventListener('change', toggleOptionsBox);
        }

        toggleOptionsBox();
    });
</script>
@endpush
