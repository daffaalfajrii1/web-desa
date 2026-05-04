@extends('layouts.admin')

@section('title', 'Banner Desa')
@section('page_title', 'Banner Desa')

@section('content')
@once
    <style>
        .village-banner-card {
            border: 0;
            border-radius: 10px;
            overflow: hidden;
            transition: transform .15s ease, box-shadow .15s ease;
        }

        .village-banner-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 24px rgba(15, 23, 42, .12);
        }

        .village-banner-media {
            aspect-ratio: 16 / 6;
            background: #e5e7eb;
            overflow: hidden;
        }

        .village-banner-media img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
        }

        .village-banner-title {
            min-height: 32px;
            font-weight: 700;
            color: #111827;
            word-break: break-word;
        }

        .village-banner-subtitle {
            min-height: 40px;
            color: #6b7280;
            font-size: 14px;
        }
    </style>
@endonce

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center flex-wrap">
        <h3 class="card-title mb-0">Carousel / Banner Public</h3>
        <div class="mt-2 mt-md-0">
            <a href="{{ route('admin.settings.desa.edit') }}" class="btn btn-outline-secondary btn-sm mr-1">
                <i class="fas fa-building mr-1"></i> Identitas Desa
            </a>
            <a href="{{ route('admin.settings.desa-banners.create') }}" class="btn btn-primary btn-sm">
                <i class="fas fa-plus mr-1"></i> Tambah Banner
            </a>
        </div>
    </div>

    <div class="card-body">
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <div class="alert alert-light border">
            Rekomendasi ukuran banner: 1600x600 px, rasio landscape, format JPG/PNG/WebP.
        </div>

        <div class="row">
            @forelse($banners as $banner)
                <div class="col-xl-4 col-lg-6 mb-4">
                    <div class="card village-banner-card h-100">
                        <div class="village-banner-media">
                            <img src="{{ $banner->image_url }}" alt="{{ $banner->title ?: 'Banner Desa' }}">
                        </div>

                        <div class="card-body">
                            <div class="mb-2">
                                <span class="badge {{ $banner->is_active ? 'badge-success' : 'badge-secondary' }}">
                                    {{ $banner->is_active ? 'Aktif' : 'Nonaktif' }}
                                </span>
                                <span class="badge badge-light border">Urutan: {{ $banner->sort_order }}</span>
                            </div>

                            <div class="village-banner-title">
                                {{ $banner->title ?: 'Tanpa judul' }}
                            </div>
                            <div class="village-banner-subtitle">
                                {{ $banner->subtitle ?: 'Tanpa subjudul' }}
                            </div>
                        </div>

                        <div class="card-footer bg-white d-flex justify-content-between align-items-center">
                            <form action="{{ route('admin.settings.desa-banners.toggle', ['village_banner' => $banner->id]) }}" method="POST" class="mb-0">
                                @csrf
                                @method('PUT')
                                <button type="submit" class="btn btn-outline-{{ $banner->is_active ? 'secondary' : 'success' }} btn-sm">
                                    <i class="fas fa-power-off mr-1"></i> {{ $banner->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                                </button>
                            </form>

                            <div>
                                <a href="{{ route('admin.settings.desa-banners.edit', ['village_banner' => $banner->id]) }}" class="btn btn-warning btn-sm" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('admin.settings.desa-banners.destroy', ['village_banner' => $banner->id]) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin hapus banner ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm" title="Hapus">
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
                        <div>Belum ada banner desa.</div>
                    </div>
                </div>
            @endforelse
        </div>

        <div class="mt-2">
            {{ $banners->links() }}
        </div>
    </div>
</div>
@endsection
