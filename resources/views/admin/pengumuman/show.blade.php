@extends('layouts.admin')

@section('title', 'Detail Pengumuman')
@section('page_title', 'Detail Pengumuman')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h3 class="card-title mb-0">{{ $item->title }}</h3>
        <div>
            <a href="{{ route('admin.pengumuman.edit', $item->id) }}" class="btn btn-warning btn-sm">
                <i class="fas fa-edit"></i> Edit
            </a>
            <a href="{{ route('admin.pengumuman.index') }}" class="btn btn-secondary btn-sm">
                Kembali
            </a>
        </div>
    </div>

    <div class="card-body">
        <div class="mb-3">
            <strong>Slug:</strong> {{ $item->slug }}<br>
            <strong>Penulis:</strong> {{ $item->author?->name ?? '-' }}<br>
            <strong>Status:</strong> {{ ucfirst($item->status) }}<br>
            <strong>Views:</strong> {{ $item->views }}<br>
            <strong>Tanggal Upload:</strong> {{ $item->created_at?->format('d M Y H:i') ?? '-' }}
        </div>

        @if($item->featured_image)
            <div class="mb-4">
                <img src="{{ asset('storage/' . $item->featured_image) }}" alt="{{ $item->title }}" style="max-width: 320px;" class="img-fluid rounded">
            </div>
        @endif

        @if($item->excerpt)
            <div class="mb-3">
                <strong>Ringkasan:</strong>
                <p class="mb-0">{{ $item->excerpt }}</p>
            </div>
        @endif

        <hr>

        <div class="mb-4">
            {!! $item->content !!}
        </div>

        @if($item->attachment)
            <a href="{{ asset('storage/' . $item->attachment) }}" target="_blank" class="btn btn-danger">
                <i class="fas fa-file-pdf"></i> Buka Lampiran PDF
            </a>
        @endif
    </div>
</div>
@endsection