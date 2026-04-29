<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            <label>Nama Program <span class="text-danger">*</span></label>
            <input type="text" name="name" required class="form-control @error('name') is-invalid @enderror"
                   value="{{ old('name', $item->name ?? '') }}">
            @error('name')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <div class="col-md-3">
        <div class="form-group">
            <label>Tahun <span class="text-danger">*</span></label>
            <input type="text" name="year" required class="form-control @error('year') is-invalid @enderror"
                   value="{{ old('year', $item->year ?? date('Y')) }}">
            @error('year')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <div class="col-md-3">
        <div class="form-group">
            <label>Periode</label>
            <input type="text" name="period" class="form-control @error('period') is-invalid @enderror"
                   value="{{ old('period', $item->period ?? '') }}"
                   placeholder="Contoh: Tahap 1 / Semester 1">
            @error('period')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <div class="col-md-4">
        <div class="form-group">
            <label>Kuota <span class="text-danger">*</span></label>
            <input type="number" min="0" name="quota" required class="form-control @error('quota') is-invalid @enderror"
                   value="{{ old('quota', $item->quota ?? 0) }}">
            @error('quota')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <div class="col-md-4">
        <div class="form-group">
            <label>Tanggal Mulai</label>
            <input type="date" name="start_date" class="form-control @error('start_date') is-invalid @enderror"
                   value="{{ old('start_date', isset($item) && $item->start_date ? $item->start_date->format('Y-m-d') : '') }}">
            @error('start_date')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <div class="col-md-4">
        <div class="form-group">
            <label>Tanggal Selesai</label>
            <input type="date" name="end_date" class="form-control @error('end_date') is-invalid @enderror"
                   value="{{ old('end_date', isset($item) && $item->end_date ? $item->end_date->format('Y-m-d') : '') }}">
            @error('end_date')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <div class="col-md-12">
        <div class="form-group">
            <label>Deskripsi</label>
            <textarea name="description" rows="4" class="form-control @error('description') is-invalid @enderror">{{ old('description', $item->description ?? '') }}</textarea>
            @error('description')
                <div class="invalid-feedback d-block">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <div class="col-md-12">
        <div class="form-check">
            <input type="checkbox" name="is_active" value="1" class="form-check-input" id="is_active"
                {{ old('is_active', $item->is_active ?? true) ? 'checked' : '' }}>
            <label class="form-check-label" for="is_active">Program aktif</label>
        </div>
    </div>
</div>