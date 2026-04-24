<div class="row">
    <div class="col-md-8">
        <div class="form-group">
            <label>Judul Agenda <span class="text-danger">*</span></label>
            <input type="text" name="title" required class="form-control @error('title') is-invalid @enderror"
                value="{{ old('title', $item->title ?? '') }}">
            @error('title')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <div class="col-md-4">
        <div class="form-group">
            <label>Slug</label>
            <input type="text" name="slug" id="slug" class="form-control @error('slug') is-invalid @enderror"
                value="{{ old('slug', $item->slug ?? '') }}">
            @error('slug')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
            <small class="text-muted">Kosongkan bila ingin otomatis.</small>
        </div>
    </div>

    <div class="col-md-12">
        <div class="form-group">
            <label>Deskripsi</label>
            <textarea name="description" id="editor" class="form-control @error('description') is-invalid @enderror">{{ old('description', $item->description ?? '') }}</textarea>
            @error('description')
                <div class="invalid-feedback d-block">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <div class="col-md-4">
        <div class="form-group">
            <label>Tanggal Mulai <span class="text-danger">*</span></label>
            <input type="date" name="start_date" required class="form-control @error('start_date') is-invalid @enderror"
                value="{{ old('start_date', isset($item->start_date) ? $item->start_date->format('Y-m-d') : '') }}">
            @error('start_date')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <div class="col-md-4">
        <div class="form-group">
            <label>Tanggal Selesai</label>
            <input type="date" name="end_date" class="form-control @error('end_date') is-invalid @enderror"
                value="{{ old('end_date', isset($item->end_date) ? $item->end_date->format('Y-m-d') : '') }}">
            @error('end_date')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <div class="col-md-4">
        <div class="form-group">
            <label>Status <span class="text-danger">*</span></label>
            <select name="status" required class="form-control @error('status') is-invalid @enderror">
                <option value="draft" {{ old('status', $item->status ?? 'draft') === 'draft' ? 'selected' : '' }}>Draft</option>
                <option value="published" {{ old('status', $item->status ?? '') === 'published' ? 'selected' : '' }}>Published</option>
            </select>
            @error('status')
                <div class="invalid-feedback d-block">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <div class="col-md-3">
        <div class="form-group">
            <label>Jam Mulai</label>
            <input type="time" name="start_time" class="form-control @error('start_time') is-invalid @enderror"
                value="{{ old('start_time', $item->start_time ?? '') }}">
            @error('start_time')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <div class="col-md-3">
        <div class="form-group">
            <label>Jam Selesai</label>
            <input type="time" name="end_time" class="form-control @error('end_time') is-invalid @enderror"
                value="{{ old('end_time', $item->end_time ?? '') }}">
            @error('end_time')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <div class="col-md-3">
        <div class="form-group">
            <label>Tempat</label>
            <input type="text" name="location" class="form-control @error('location') is-invalid @enderror"
                value="{{ old('location', $item->location ?? '') }}">
            @error('location')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <div class="col-md-3">
        <div class="form-group">
            <label>Penyelenggara</label>
            <input type="text" name="organizer" class="form-control @error('organizer') is-invalid @enderror"
                value="{{ old('organizer', $item->organizer ?? '') }}">
            @error('organizer')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group">
            <label>Kontak Person</label>
            <input type="text" name="contact_person" class="form-control @error('contact_person') is-invalid @enderror"
                value="{{ old('contact_person', $item->contact_person ?? '') }}">
            @error('contact_person')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group">
            <label>Foto Agenda</label>
            <input type="file" name="featured_image" class="form-control-file @error('featured_image') is-invalid @enderror">
            @error('featured_image')
                <div class="text-danger small">{{ $message }}</div>
            @enderror

            @if(!empty($item?->featured_image))
                <div class="mt-2">
                    <img src="{{ asset('storage/' . $item->featured_image) }}" alt="foto agenda" width="150">
                </div>
            @endif
        </div>
    </div>
</div>