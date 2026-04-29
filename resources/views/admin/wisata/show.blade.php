@extends('layouts.admin')

@section('title', 'Detail Wisata')
@section('page_title', 'Detail Wisata')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h3 class="card-title mb-0">{{ $item->title }}</h3>
        <div>
            <a href="{{ route('admin.wisata.edit', ['tourism' => $item->id]) }}" class="btn btn-warning btn-sm">
                <i class="fas fa-edit"></i> Edit
            </a>
            <a href="{{ route('admin.wisata.index') }}" class="btn btn-secondary btn-sm">
                Kembali
            </a>
        </div>
    </div>

    <div class="card-body">
        @if($item->main_image)
            <div class="mb-4">
                <img src="{{ asset('storage/' . $item->main_image) }}" class="img-fluid rounded" style="max-width:320px;" alt="{{ $item->title }}">
            </div>
        @endif

        <div class="row">
            <div class="col-md-6">
                <p><strong>Alamat:</strong> {{ $item->address ?? '-' }}</p>
                <p><strong>Kontak:</strong> {{ $item->contact_person ?? '-' }}</p>
                <p><strong>No. Kontak:</strong> {{ $item->contact_phone ?? '-' }}</p>
                <p><strong>Views:</strong> {{ $item->views }}</p>
            </div>
            <div class="col-md-6">
                <p><strong>Hari Buka:</strong> {{ $item->open_days ?? '-' }}</p>
                <p><strong>Hari Tutup:</strong> {{ $item->closed_days ?? '-' }}</p>
                <p><strong>Jam Buka:</strong> {{ $item->open_time ?? '-' }}</p>
                <p><strong>Jam Tutup:</strong> {{ $item->close_time ?? '-' }}</p>
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

        @if($item->facilities)
            <hr>
            <p><strong>Fasilitas:</strong></p>
            <div>{!! nl2br(e($item->facilities)) !!}</div>
        @endif

        @if($item->map_embed)
            <hr>
            <p><strong>Embed Peta:</strong></p>
            <div class="embed-responsive embed-responsive-16by9">
                {!! $item->map_embed !!}
            </div>
        @endif

        @if($item->images->count())
            <hr>
            <h5>Galeri Wisata</h5>
            <div class="row">
                @foreach($item->images as $image)
                    <div class="col-md-3 mb-3">
                        <img src="{{ asset('storage/' . $image->image_path) }}" class="img-fluid rounded" alt="galeri wisata">
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>
@endsection