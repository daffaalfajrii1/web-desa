@extends('layouts.admin')

@section('title', 'Detail Produk Lapak')
@section('page_title', 'Detail Produk Lapak')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h3 class="card-title mb-0">{{ $item->title }}</h3>
        <div>
            <a href="{{ route('admin.lapak.edit', $item->id) }}" class="btn btn-warning btn-sm">
                <i class="fas fa-edit"></i> Edit
            </a>
            <a href="{{ route('admin.lapak.index') }}" class="btn btn-secondary btn-sm">
                Kembali
            </a>
        </div>
    </div>

    <div class="card-body">
        @if($item->main_image)
            <div class="mb-4">
                <img src="{{ asset('storage/' . $item->main_image) }}" class="img-fluid rounded" style="max-width:300px;">
            </div>
        @endif

        <div class="row">
            <div class="col-md-6">
                <p><strong>Kategori:</strong> {{ $item->category?->name ?? 'Tak Berkategori' }}</p>
                <p><strong>Harga:</strong> Rp {{ number_format((float) $item->price, 0, ',', '.') }}</p>
                <p><strong>Stok:</strong> {{ $item->stock ?? '-' }}</p>
                <p><strong>Status:</strong> {{ $item->status === 'available' ? 'Tersedia' : 'Habis' }}</p>
                <p><strong>Unggulan:</strong> {{ $item->is_featured ? 'Ya' : 'Tidak' }}</p>
            </div>
            <div class="col-md-6">
                <p><strong>Penjual:</strong> {{ $item->seller_name ?? '-' }}</p>
                <p><strong>WhatsApp:</strong> {{ $item->whatsapp ?? '-' }}</p>
                <p><strong>Lokasi:</strong> {{ $item->location ?? '-' }}</p>
                <p><strong>Views:</strong> {{ $item->views }}</p>
            </div>
        </div>

        @if($item->excerpt)
            <hr>
            <p><strong>Ringkasan:</strong></p>
            <p>{{ $item->excerpt }}</p>
        @endif

        @if($item->description)
            <hr>
            <div>{!! $item->description !!}</div>
        @endif

        @if($item->images->count())
            <hr>
            <h5>Galeri Produk</h5>
            <div class="row">
                @foreach($item->images as $image)
                    <div class="col-md-3 mb-3">
                        <img src="{{ asset('storage/' . $image->image_path) }}" class="img-fluid rounded">
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>
@endsection