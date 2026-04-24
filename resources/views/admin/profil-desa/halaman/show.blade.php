@extends('layouts.admin')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h3 class="card-title mb-0">{{ $page->title }}</h3>
        <div>
            <a href="{{ route('admin.profil-desa.halaman.edit', $page->id) }}" class="btn btn-warning btn-sm">
                <i class="fas fa-edit"></i> Edit
            </a>
            <a href="{{ route('admin.profil-desa.halaman.index') }}" class="btn btn-secondary btn-sm">
                Kembali
            </a>
        </div>
    </div>

    <div class="card-body">
        <div class="mb-3">
            <strong>Slug:</strong> {{ $page->slug }}<br>
            <strong>Status:</strong> {{ ucfirst($page->status) }}<br>
            <strong>Publish:</strong> {{ $page->published_at?->format('d M Y H:i') ?? '-' }}
        </div>

        @if($page->featured_image)
            <div class="mb-4">
                <img src="{{ asset('storage/' . $page->featured_image) }}" alt="{{ $page->title }}" style="max-width: 320px;" class="img-fluid rounded">
            </div>
        @endif

        @if($page->excerpt)
            <div class="mb-3">
                <strong>Ringkasan:</strong>
                <p class="mb-0">{{ $page->excerpt }}</p>
            </div>
        @endif

        <hr>

        <div>
            {!! $page->content !!}
        </div>
    </div>
</div>
@endsection