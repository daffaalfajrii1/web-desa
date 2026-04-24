@extends('layouts.admin')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h3 class="card-title mb-0">{{ $item->title }}</h3>
        <div>
            <a href="{{ route('admin.produk-hukum.edit', $item->id) }}" class="btn btn-warning btn-sm">
                <i class="fas fa-edit"></i> Edit
            </a>
            <a href="{{ route('admin.produk-hukum.index') }}" class="btn btn-secondary btn-sm">
                Kembali
            </a>
        </div>
    </div>

    <div class="card-body">
        <div class="mb-3">
            <strong>Slug:</strong> {{ $item->slug }}<br>
            <strong>Kategori:</strong> {{ $item->category?->name ?? 'Tak Berkategori' }}<br>
            <strong>Nomor:</strong> {{ $item->number ?? '-' }}<br>
            <strong>Jenis:</strong> {{ $item->document_type ?? '-' }}<br>
            <strong>Tanggal:</strong> {{ $item->published_date?->format('d M Y') ?? '-' }}<br>
            <strong>Status:</strong> {{ ucfirst($item->status) }}<br>
            <strong>Views:</strong> {{ $item->views }}<br>
            <strong>Penulis:</strong> {{ $item->author?->name ?? '-' }}
        </div>

        @if($item->description)
            <div class="mb-3">
                <strong>Deskripsi:</strong>
                <p class="mb-0">{{ $item->description }}</p>
            </div>
        @endif

        @if($item->file_path)
            <div class="mb-3">
                <a href="{{ asset('storage/' . $item->file_path) }}" target="_blank" class="btn btn-danger">
                    <i class="fas fa-file-pdf"></i> Buka File PDF
                </a>
            </div>
        @endif
    </div>
</div>
@endsection