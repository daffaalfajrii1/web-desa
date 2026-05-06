@php
    $selectedType = old('media_type', $item->media_type ?? 'photo');
    $selectedStatus = old('status', $item->status ?? 'draft');
    $isEdit = !empty($item);
@endphp

@once
    <style>
        .gallery-dropzone {
            position: relative;
            border: 2px dashed #b6c2d2;
            border-radius: 12px;
            background: #f8fafc;
            cursor: pointer;
            min-height: 140px;
            padding: 24px;
            transition: border-color .15s ease, background .15s ease, box-shadow .15s ease;
        }

        .gallery-dropzone:hover,
        .gallery-dropzone.is-dragover {
            border-color: #0d6efd;
            background: #eef6ff;
            box-shadow: 0 6px 18px rgba(13, 110, 253, .12);
        }

        .gallery-dropzone-icon {
            width: 58px;
            height: 58px;
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: #e8f1ff;
            color: #0d6efd;
            font-size: 26px;
        }

        .gallery-preview-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(132px, 1fr));
            gap: 12px;
        }

        .gallery-preview-card {
            border: 1px solid #e5e7eb;
            border-radius: 10px;
            overflow: hidden;
            background: #fff;
        }

        .gallery-preview-card img {
            width: 100%;
            aspect-ratio: 4 / 3;
            object-fit: cover;
            display: block;
        }

        .gallery-preview-meta {
            padding: 8px;
            min-height: 66px;
        }

        .gallery-preview-name {
            color: #111827;
            font-size: 12px;
            font-weight: 600;
            line-height: 1.3;
            min-height: 32px;
            word-break: break-word;
        }

        .gallery-current-media {
            border: 1px solid #e5e7eb;
            border-radius: 10px;
            padding: 10px;
            background: #fff;
            transition: opacity .15s ease, border-color .15s ease, background .15s ease;
        }

        .gallery-current-media.is-marked-delete {
            border-color: #dc3545;
            background: #fff5f5;
            opacity: .72;
        }

        .gallery-current-media img,
        .gallery-video-preview img {
            width: 100%;
            border-radius: 8px;
            object-fit: cover;
        }

        .gallery-video-preview {
            border: 1px solid #e5e7eb;
            border-radius: 10px;
            padding: 10px;
            background: #fff;
        }

        .gallery-video-thumb {
            position: relative;
            overflow: hidden;
            border-radius: 8px;
            background: #e5e7eb;
        }

        .gallery-video-thumb img {
            aspect-ratio: 16 / 9;
            display: block;
        }

        .gallery-video-play {
            position: absolute;
            inset: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-size: 42px;
            background: rgba(0, 0, 0, .22);
            text-shadow: 0 4px 14px rgba(0, 0, 0, .35);
        }
    </style>
@endonce

<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            <label>Judul Galeri <span class="text-danger">*</span></label>
            <input type="text" name="title" required class="form-control @error('title') is-invalid @enderror"
                   value="{{ old('title', $item->title ?? '') }}">
            @error('title')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <div class="col-md-3">
        <div class="form-group">
            <label>Slug</label>
            <input type="text" name="slug" class="form-control @error('slug') is-invalid @enderror"
                   value="{{ old('slug', $item->slug ?? '') }}">
            @error('slug')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
            <small class="text-muted">Kosongkan bila ingin otomatis.</small>
        </div>
    </div>

    <div class="col-md-3">
        <div class="form-group">
            <label>Tipe Media <span class="text-danger">*</span></label>
            <select name="media_type" id="media_type" class="form-control @error('media_type') is-invalid @enderror">
                <option value="photo" {{ $selectedType === 'photo' ? 'selected' : '' }}>Foto</option>
                <option value="video" {{ $selectedType === 'video' ? 'selected' : '' }}>Video YouTube</option>
            </select>
            @error('media_type')
                <div class="invalid-feedback d-block">{{ $message }}</div>
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

    <div class="col-md-6" id="photo_fields">
        <div class="form-group">
            <label>{{ $isEdit ? 'Tambah foto ke album' : 'Upload foto (satu album)' }} @if (! $isEdit)<span class="text-danger">*</span>@endif</label>
            <p class="small text-muted mb-2">{{ $isEdit ? 'Ambil beberapa file sekaligus atau seret foto ke kotak hijau untuk menambah ke batch yang sama.' : 'Semua foto di bawah akan masuk <strong>satu album</strong> (satu kartu di daftar). Pilih beberapa file sekali atau tambah lagi dengan kotak geser atau unggahan kedua.' }}</p>
            <input
                type="file"
                id="gallery_photo_input"
                name="{{ $isEdit ? 'new_images[]' : 'images[]' }}"
                accept="image/jpeg,image/png,image/webp"
                class="form-control @error('images') is-invalid @enderror @error('images.*') is-invalid @enderror @error('new_images') is-invalid @enderror @error('new_images.*') is-invalid @enderror"
                multiple
            >

            <div id="gallery_dropzone" class="gallery-dropzone text-center mt-3" role="region" aria-label="Area seret foto">
                <div class="gallery-dropzone-inner" style="pointer-events: none;">
                    <div class="gallery-dropzone-icon mb-2">
                        <i class="fas fa-arrows-alt"></i>
                    </div>
                    <div class="font-weight-bold mb-1">Seret &amp; lepas foto ke sini</div>
                    <div class="text-muted small mb-0">Menambahkan ke daftar unggahan di atas tanpa menghapus pilihan sebelumnya</div>
                </div>
            </div>

            <div id="gallery_file_summary" class="alert alert-light border mt-3 mb-3">
                {{ $isEdit ? 'Belum ada foto tambahan dipilih.' : 'Belum ada foto dipilih.' }}
            </div>

            <div id="gallery_preview_grid" class="gallery-preview-grid mb-3"></div>

            @error('images')
                <div class="text-danger small">{{ $message }}</div>
            @enderror
            @error('images.*')
                <div class="text-danger small">{{ $message }}</div>
            @enderror
            @error('new_images')
                <div class="text-danger small">{{ $message }}</div>
            @enderror
            @error('new_images.*')
                <div class="text-danger small">{{ $message }}</div>
            @enderror
            @error('remove_photos.*')
                <div class="text-danger small">{{ $message }}</div>
            @enderror

            @if($isEdit && $item->is_photo && $item->photoPathsList() !== [])
                <div id="gallery_existing_photos" class="mt-3">
                    <div class="small text-muted font-weight-bold mb-2">Foto dalam album (centang untuk hapus)</div>
                    <div class="gallery-preview-grid">
                        @foreach ($item->photoPathsList() as $path)
                            <div class="gallery-preview-card gallery-current-media">
                                <img src="{{ asset('storage/'.$path) }}" alt="">
                                <div class="gallery-preview-meta">
                                    <label class="small mb-0 text-danger d-flex align-items-center" style="gap:6px;">
                                        <input type="checkbox" name="remove_photos[]" value="{{ $path }}" class="m-0">
                                        Hapus
                                    </label>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </div>

    <div class="col-md-6" id="video_fields">
        <div class="form-group">
            <label>Link YouTube</label>
            <input type="url" name="youtube_url" id="youtube_url" class="form-control @error('youtube_url') is-invalid @enderror"
                   value="{{ old('youtube_url', $item->youtube_url ?? '') }}" placeholder="https://www.youtube.com/watch?v=...">
            @error('youtube_url')
                <div class="invalid-feedback d-block">{{ $message }}</div>
            @enderror

            <div id="youtube_preview" class="gallery-video-preview mt-3" data-existing-id="{{ $item->youtube_id ?? '' }}">
                <div class="small text-muted font-weight-bold mb-2">Preview Video</div>
                <div id="youtube_preview_content">
                    @if(!empty($item?->youtube_thumbnail_url))
                        <div class="gallery-video-thumb">
                            <img src="{{ $item->youtube_thumbnail_url }}" alt="{{ $item->title }}">
                            <span class="gallery-video-play"><i class="fas fa-play-circle"></i></span>
                        </div>
                    @else
                        <div class="text-muted py-4 text-center">Tempel link YouTube untuk melihat preview.</div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="form-group">
            <label>Lokasi</label>
            <input type="text" name="location" class="form-control @error('location') is-invalid @enderror"
                   value="{{ old('location', $item->location ?? '') }}">
            @error('location')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <div class="col-md-3">
        <div class="form-group">
            <label>Tanggal Dokumentasi</label>
            <input type="date" name="taken_at" class="form-control @error('taken_at') is-invalid @enderror"
                   value="{{ old('taken_at', !empty($item?->taken_at) ? $item->taken_at->format('Y-m-d') : '') }}">
            @error('taken_at')
                <div class="invalid-feedback d-block">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <div class="col-md-3">
        <div class="form-group">
            <label>Status <span class="text-danger">*</span></label>
            <select name="status" class="form-control @error('status') is-invalid @enderror">
                <option value="draft" {{ $selectedStatus === 'draft' ? 'selected' : '' }}>Draft</option>
                <option value="published" {{ $selectedStatus === 'published' ? 'selected' : '' }}>Published</option>
            </select>
            @error('status')
                <div class="invalid-feedback d-block">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <div class="col-md-12">
        <div class="form-check">
            <input type="checkbox" name="is_featured" value="1" class="form-check-input" id="is_featured"
                {{ old('is_featured', $item->is_featured ?? false) ? 'checked' : '' }}>
            <label for="is_featured" class="form-check-label">Tampilkan sebagai galeri unggulan</label>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const mediaType = document.getElementById('media_type');
        const photoFields = document.getElementById('photo_fields');
        const videoFields = document.getElementById('video_fields');
        const fileInput = document.getElementById('gallery_photo_input');
        const dropzone = document.getElementById('gallery_dropzone');
        const previewGrid = document.getElementById('gallery_preview_grid');
        const fileSummary = document.getElementById('gallery_file_summary');
        const youtubeInput = document.getElementById('youtube_url');
        const youtubePreview = document.getElementById('youtube_preview_content');
        const isEdit = {{ $isEdit ? 'true' : 'false' }};
        /** @type {File[]} Akumulasi file agar dialog "buka file" tidak menimpa pilihan sebelumnya (penyebab satu request per foto). */
        let stagedFiles = [];

        function toggleMediaFields() {
            const isVideo = mediaType.value === 'video';
            photoFields.style.display = isVideo ? 'none' : '';
            videoFields.style.display = isVideo ? '' : 'none';
        }

        function formatFileSize(bytes) {
            if (bytes < 1024 * 1024) {
                return Math.max(1, Math.round(bytes / 1024)) + ' KB';
            }

            return (bytes / (1024 * 1024)).toFixed(1) + ' MB';
        }

        function applyStagedToInput() {
            if (typeof DataTransfer === 'undefined' || !fileInput) {
                return;
            }
            const dt = new DataTransfer();
            stagedFiles.forEach(function (file) {
                try {
                    dt.items.add(file);
                } catch (err) {
                    /* abaikan duplikat yang ditolak browser */
                }
            });
            fileInput.files = dt.files;
        }

        function renderFilePreview() {
            previewGrid.innerHTML = '';

            if (stagedFiles.length === 0) {
                fileSummary.textContent = isEdit ? 'Belum ada foto tambahan dipilih.' : 'Belum ada foto dipilih.';
                return;
            }

            fileSummary.textContent = isEdit
                ? stagedFiles.length + ' foto baru siap ditambahkan.'
                : stagedFiles.length + ' foto akan masuk ke satu album.';

            stagedFiles.forEach(function (file, index) {
                const card = document.createElement('div');
                card.className = 'gallery-preview-card';

                const image = document.createElement('img');
                image.src = URL.createObjectURL(file);
                image.alt = file.name;
                image.onload = function () {
                    URL.revokeObjectURL(image.src);
                };

                const meta = document.createElement('div');
                meta.className = 'gallery-preview-meta';

                const name = document.createElement('div');
                name.className = 'gallery-preview-name';
                name.textContent = file.name;

                const size = document.createElement('div');
                size.className = 'text-muted small mb-2';
                size.textContent = formatFileSize(file.size);

                const remove = document.createElement('button');
                remove.type = 'button';
                remove.className = 'btn btn-outline-danger btn-xs btn-block';
                remove.innerHTML = '<i class="fas fa-times mr-1"></i> Hapus';
                remove.addEventListener('click', function () {
                    stagedFiles.splice(index, 1);
                    applyStagedToInput();
                    renderFilePreview();
                });

                meta.appendChild(name);
                meta.appendChild(size);
                meta.appendChild(remove);
                card.appendChild(image);
                card.appendChild(meta);
                previewGrid.appendChild(card);
            });
        }

        function appendImageFiles(fileList) {
            const imageFiles = Array.from(fileList).filter(function (file) {
                return file.type.startsWith('image/');
            });
            imageFiles.forEach(function (f) {
                stagedFiles.push(f);
            });
            applyStagedToInput();
            renderFilePreview();
        }

        function extractYoutubeId(url) {
            try {
                const parsed = new URL(url);
                const host = parsed.hostname.replace(/^www\./, '').toLowerCase();
                const path = parsed.pathname.replace(/^\/+|\/+$/g, '');

                if (host === 'youtu.be') {
                    return path.split('/')[0];
                }

                const isYoutubeHost = host === 'youtube.com'
                    || host.endsWith('.youtube.com')
                    || host === 'youtube-nocookie.com'
                    || host.endsWith('.youtube-nocookie.com');

                if (!isYoutubeHost) {
                    return null;
                }

                if (parsed.searchParams.get('v')) {
                    return parsed.searchParams.get('v');
                }

                const segments = path.split('/');
                if (['embed', 'shorts', 'live'].includes(segments[0])) {
                    return segments[1];
                }
            } catch (error) {
                return null;
            }

            return null;
        }

        function updateYoutubePreview() {
            if (!youtubeInput || !youtubePreview) {
                return;
            }
            const youtubeId = extractYoutubeId(youtubeInput.value);

            if (!youtubeId || !/^[A-Za-z0-9_-]{11}$/.test(youtubeId)) {
                youtubePreview.innerHTML = '<div class="text-muted py-4 text-center">Tempel link YouTube untuk melihat preview.</div>';
                return;
            }

            youtubePreview.innerHTML =
                '<div class="gallery-video-thumb">' +
                '<img src="https://img.youtube.com/vi/' + youtubeId + '/hqdefault.jpg" alt="Preview video">' +
                '<span class="gallery-video-play"><i class="fas fa-play-circle"></i></span>' +
                '</div>';
        }

        mediaType.addEventListener('change', toggleMediaFields);

        if (fileInput) {
            fileInput.addEventListener('change', function (event) {
                appendImageFiles(event.target.files || []);
            });
        }

        if (dropzone) {
            ['dragenter', 'dragover'].forEach(function (eventName) {
                dropzone.addEventListener(eventName, function (event) {
                    event.preventDefault();
                    dropzone.classList.add('is-dragover');
                });
            });
            dropzone.addEventListener('dragleave', function (event) {
                event.preventDefault();
                dropzone.classList.remove('is-dragover');
            });
            dropzone.addEventListener('drop', function (event) {
                event.preventDefault();
                dropzone.classList.remove('is-dragover');
                appendImageFiles(event.dataTransfer.files);
            });
        }

        if (youtubeInput) {
            youtubeInput.addEventListener('input', updateYoutubePreview);
        }

        toggleMediaFields();
        renderFilePreview();
        if (youtubeInput) {
            updateYoutubePreview();
        }
    });
</script>
@endpush
