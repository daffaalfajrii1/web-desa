@extends('layouts.admin')

@section('title', 'Galeri Desa')
@section('page_title', 'Galeri Desa')

@section('content')
<style>
    .gallery-card {
        border: 0;
        border-radius: 10px;
        overflow: hidden;
        transition: transform .15s ease, box-shadow .15s ease;
    }

    .gallery-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 24px rgba(15, 23, 42, .12);
    }

    .gallery-media {
        position: relative;
        display: block;
        width: 100%;
        aspect-ratio: 16 / 10;
        background: #e5e7eb;
        overflow: hidden;
    }

    .gallery-media img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform .2s ease;
    }

    .gallery-card:hover .gallery-media img {
        transform: scale(1.035);
    }

    .gallery-play {
        position: absolute;
        inset: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #fff;
        background: rgba(0, 0, 0, .24);
        font-size: 44px;
        text-shadow: 0 4px 14px rgba(0, 0, 0, .35);
    }

    .gallery-placeholder {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 100%;
        height: 100%;
        color: #6b7280;
        font-size: 38px;
    }

    .gallery-title {
        min-height: 42px;
        margin-bottom: .55rem;
        font-weight: 700;
        line-height: 1.35;
        color: #111827;
        word-break: break-word;
    }

    .gallery-description {
        min-height: 42px;
        color: #6b7280;
        font-size: 14px;
    }

    .gallery-meta {
        color: #6b7280;
        font-size: 13px;
    }
</style>

<div class="row">
    <div class="col-lg-3 col-md-6">
        <div class="small-box bg-info">
            <div class="inner">
                <h3>{{ $stats['total'] }}</h3>
                <p>Total Galeri</p>
            </div>
            <div class="icon"><i class="fas fa-images"></i></div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6">
        <div class="small-box bg-success">
            <div class="inner">
                <h3>{{ $stats['photo'] }}</h3>
                <p>Foto</p>
            </div>
            <div class="icon"><i class="fas fa-camera"></i></div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6">
        <div class="small-box bg-danger">
            <div class="inner">
                <h3>{{ $stats['video'] }}</h3>
                <p>Video</p>
            </div>
            <div class="icon"><i class="fab fa-youtube"></i></div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6">
        <div class="small-box bg-primary">
            <div class="inner">
                <h3>{{ $stats['published'] }}</h3>
                <p>Published</p>
            </div>
            <div class="icon"><i class="fas fa-check-circle"></i></div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h3 class="card-title mb-0">Koleksi Galeri</h3>
        <a href="{{ route('admin.galeri.create') }}" class="btn btn-primary btn-sm">
            <i class="fas fa-plus"></i> Tambah Galeri
        </a>
    </div>

    <div class="card-body">
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <form method="GET" class="mb-4">
            <div class="row">
                <div class="col-md-4 mb-2">
                    <input type="text" name="search" class="form-control" placeholder="Cari judul, lokasi, atau link..." value="{{ request('search') }}">
                </div>
                <div class="col-md-3 mb-2">
                    <select name="media_type" class="form-control">
                        <option value="">Semua Media</option>
                        <option value="photo" {{ request('media_type') === 'photo' ? 'selected' : '' }}>Foto</option>
                        <option value="video" {{ request('media_type') === 'video' ? 'selected' : '' }}>Video YouTube</option>
                    </select>
                </div>
                <div class="col-md-3 mb-2">
                    <select name="status" class="form-control">
                        <option value="">Semua Status</option>
                        <option value="published" {{ request('status') === 'published' ? 'selected' : '' }}>Published</option>
                        <option value="draft" {{ request('status') === 'draft' ? 'selected' : '' }}>Draft</option>
                    </select>
                </div>
                <div class="col-md-2 mb-2">
                    <button class="btn btn-primary btn-block" type="submit">
                        <i class="fas fa-filter"></i> Filter
                    </button>
                </div>
            </div>
            @if(request()->hasAny(['search', 'media_type', 'status']))
                <a href="{{ route('admin.galeri.index') }}" class="btn btn-secondary btn-sm">
                    <i class="fas fa-sync-alt"></i> Reset
                </a>
            @endif
        </form>

        <div class="row">
            @forelse($items as $item)
                <div class="col-xl-3 col-lg-4 col-md-6 mb-4">
                    <div class="card gallery-card h-100">
                        <a href="{{ route('admin.galeri.show', ['gallery' => $item->id]) }}" class="gallery-media">
                            @if($item->media_url)
                                <img src="{{ $item->media_url }}" alt="{{ $item->title }}">
                            @else
                                <span class="gallery-placeholder">
                                    <i class="fas fa-image"></i>
                                </span>
                            @endif

                            @if($item->is_video)
                                <span class="gallery-play">
                                    <i class="fas fa-play-circle"></i>
                                </span>
                            @endif
                        </a>

                        <div class="card-body">
                            <div class="mb-2">
                                <span class="badge {{ $item->is_photo ? 'badge-success' : 'badge-danger' }}">
                                    {{ $item->is_photo ? 'Foto' : 'Video' }}
                                </span>
                                <span class="badge {{ $item->status === 'published' ? 'badge-primary' : 'badge-secondary' }}">
                                    {{ $item->status_label }}
                                </span>
                                @if($item->is_featured)
                                    <span class="badge badge-warning">Unggulan</span>
                                @endif
                                @if($item->is_photo && $item->photoCount() > 1)
                                    <span class="badge badge-secondary">{{ $item->photoCount() }} foto</span>
                                @endif
                            </div>

                            <div class="gallery-title">{{ $item->title }}</div>
                            <div class="gallery-description">
                                {{ \Illuminate\Support\Str::limit($item->description ?: 'Belum ada deskripsi.', 72) }}
                            </div>
                            <div class="gallery-meta mt-3">
                                <div><i class="fas fa-map-marker-alt mr-1"></i> {{ $item->location ?: '-' }}</div>
                                <div><i class="fas fa-calendar mr-1"></i> {{ $item->taken_at?->format('d-m-Y') ?: '-' }}</div>
                            </div>
                        </div>

                        <div class="card-footer bg-white d-flex justify-content-between align-items-center">
                            <span class="text-muted small">{{ $item->published_at?->format('d-m-Y H:i') ?: '-' }}</span>
                            <div>
                                <a href="{{ route('admin.galeri.show', ['gallery' => $item->id]) }}" class="btn btn-info btn-sm" title="Detail">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('admin.galeri.edit', ['gallery' => $item->id]) }}" class="btn btn-warning btn-sm" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('admin.galeri.destroy', ['gallery' => $item->id]) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin hapus galeri ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-danger btn-sm" type="submit" title="Hapus">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="text-center text-muted py-5">
                        <i class="fas fa-images fa-3x mb-3"></i>
                        <div>Belum ada data galeri.</div>
                    </div>
                </div>
            @endforelse
        </div>

        <div class="mt-2">
            {{ $items->links() }}
        </div>
    </div>
</div>
@endsection
