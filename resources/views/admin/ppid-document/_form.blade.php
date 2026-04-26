<div class="row">
    <div class="col-md-6">
    <div class="form-group">
        <label>Section PPID <span class="text-danger">*</span></label>
        <select name="ppid_section_id" required class="form-control @error('ppid_section_id') is-invalid @enderror">
            <option value="">-- Pilih Section --</option>
            @foreach($sections as $section)
                <option value="{{ $section->id }}"
                    {{ (string) old('ppid_section_id', $item->ppid_section_id ?? ($selectedSectionId ?? '')) === (string) $section->id ? 'selected' : '' }}>
                    [{{ str_replace('_', ' ', ucfirst($section->type)) }}] {{ $section->title }}
                </option>
            @endforeach
        </select>
        @error('ppid_section_id')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
</div>

    <div class="col-md-6">
        <div class="form-group">
            <label>Judul Dokumen <span class="text-danger">*</span></label>
            <input type="text" name="title" required class="form-control @error('title') is-invalid @enderror" value="{{ old('title', $item->title ?? '') }}">
            @error('title') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group">
            <label>Upload PDF @if(empty($item?->file_path))<span class="text-danger">*</span>@endif</label>
            <input type="file" name="file_path" accept="application/pdf" {{ empty($item?->file_path) ? 'required' : '' }} class="form-control-file @error('file_path') is-invalid @enderror">
            @error('file_path') <div class="text-danger small">{{ $message }}</div> @enderror
            @if(!empty($item?->file_path))
                <div class="mt-2">
                    <a href="{{ asset('storage/' . $item->file_path) }}" target="_blank" class="btn btn-danger btn-sm">
                        <i class="fas fa-file-pdf"></i> Lihat PDF Saat Ini
                    </a>
                </div>
            @endif
        </div>
    </div>

    <div class="col-md-3">
        <div class="form-group">
            <label>Urutan</label>
            <input type="number" name="sort_order" class="form-control @error('sort_order') is-invalid @enderror" value="{{ old('sort_order', $item->sort_order ?? 0) }}">
            @error('sort_order') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
    </div>

    <div class="col-md-3">
        <div class="form-check mt-4">
            <input type="checkbox" name="is_active" value="1" class="form-check-input" id="is_active"
                {{ old('is_active', $item->is_active ?? true) ? 'checked' : '' }}>
            <label for="is_active" class="form-check-label">Aktif</label>
        </div>
    </div>
</div>