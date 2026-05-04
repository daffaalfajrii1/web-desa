@extends('layouts.admin')

@section('title', 'Detail Galeri')
@section('page_title', 'Detail Galeri')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h3 class="card-title mb-0">{{ $item->title }}</h3>
        <div>
            <a href="{{ route('admin.galeri.edit', ['gallery' => $item->id]) }}" class="btn btn-warning btn-sm">
                <i class="fas fa-edit"></i> Edit
            </a>
            <a href="{{ route('admin.galeri.index') }}" class="btn btn-secondary btn-sm">
                Kembali
            </a>
        </div>
    </div>

    <div class="card-body">
        <div class="row">
            <div class="col-lg-8 mb-4">
                @if($item->is_photo && $item->image_path)
                    <img src="{{ asset('storage/' . $item->image_path) }}" class="img-fluid rounded w-100" style="max-height: 560px; object-fit: cover;" alt="{{ $item->title }}">
                @elseif($item->is_video && $item->youtube_embed_url)
                    <div class="embed-responsive embed-responsive-16by9 rounded">
                        <iframe class="embed-responsive-item" src="{{ $item->youtube_embed_url }}" allowfullscreen title="{{ $item->title }}"></iframe>
                    </div>
                @else
                    <div class="bg-light text-muted rounded d-flex align-items-center justify-content-center" style="height: 320px;">
                        <i class="fas fa-image fa-3x"></i>
                    </div>
                @endif

                @if($item->description)
                    <div class="mt-4">
                        <h5>Deskripsi</h5>
                        <div>{!! nl2br(e($item->description)) !!}</div>
                    </div>
                @endif
            </div>

            <div class="col-lg-4">
                <div class="card bg-light">
                    <div class="card-body">
                        <div class="mb-3">
                            <span class="badge {{ $item->is_photo ? 'badge-success' : 'badge-danger' }}">
                                {{ $item->is_photo ? 'Foto' : 'Video YouTube' }}
                            </span>
                            <span class="badge {{ $item->status === 'published' ? 'badge-primary' : 'badge-secondary' }}">
                                {{ $item->status_label }}
                            </span>
                            @if($item->is_featured)
                                <span class="badge badge-warning">Unggulan</span>
                            @endif
                        </div>

                        <p><strong>Slug:</strong><br>{{ $item->slug }}</p>
                        <p><strong>Lokasi:</strong><br>{{ $item->location ?: '-' }}</p>
                        <p><strong>Tanggal Dokumentasi:</strong><br>{{ $item->taken_at?->format('d-m-Y') ?: '-' }}</p>
                        <p><strong>Urutan:</strong><br>{{ $item->sort_order }}</p>
                        <p><strong>Dibuat Oleh:</strong><br>{{ $item->author?->name ?: '-' }}</p>
                        <p><strong>Published At:</strong><br>{{ $item->published_at?->format('d-m-Y H:i') ?: '-' }}</p>

                        @if($item->is_video && $item->youtube_url)
                            <p class="mb-0">
                                <strong>Link YouTube:</strong><br>
                                <a href="{{ $item->youtube_url }}" target="_blank" rel="noopener">{{ $item->youtube_url }}</a>
                            </p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
