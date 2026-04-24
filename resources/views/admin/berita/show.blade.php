@extends('layouts.admin')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h3 class="card-title mb-0">{{ $post->title }}</h3>
        <div>
            <a href="{{ route('admin.berita.edit', $post->id) }}" class="btn btn-warning btn-sm">
                <i class="fas fa-edit"></i> Edit
            </a>
            <a href="{{ route('admin.berita.index') }}" class="btn btn-secondary btn-sm">
                Kembali
            </a>
        </div>
    </div>

    <div class="card-body">
        <div class="mb-3">
            <strong>Slug:</strong> {{ $post->slug }}<br>
            <strong>Kategori:</strong> {{ $post->category?->name ?? 'Tak Berkategori' }}<br>
            <strong>Penulis:</strong> {{ $post->author?->name ?? '-' }}<br>
            <strong>Status:</strong> {{ ucfirst($post->status) }}<br>
            <strong>Views:</strong> {{ $post->views }}<br>
            <strong>Publish:</strong> {{ $post->published_at?->format('d M Y H:i') ?? '-' }}
        </div>

        @if($post->featured_image)
            <div class="mb-4">
                <img src="{{ asset('storage/' . $post->featured_image) }}" alt="{{ $post->title }}" style="max-width: 320px;" class="img-fluid rounded">
            </div>
        @endif

        @if($post->excerpt)
            <div class="mb-3">
                <strong>Ringkasan:</strong>
                <p class="mb-0">{{ $post->excerpt }}</p>
            </div>
        @endif

        <hr>

        <div>
            {!! $post->content !!}
        </div>
    </div>
</div>
@endsection