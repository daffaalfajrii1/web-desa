@extends('layouts.admin')
@section('title', 'Detail Section PPID')
@section('page_title', 'Detail Section PPID')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between">
        <h3 class="card-title">{{ $item->title }}</h3>
        <a href="{{ route('admin.ppid-section.index') }}" class="btn btn-secondary btn-sm">Kembali</a>
    </div>
    <div class="card-body">
        <p><strong>Jenis:</strong> {{ str_replace('_', ' ', ucfirst($item->type)) }}</p>
        <p><strong>Urutan:</strong> {{ $item->sort_order }}</p>
        <p><strong>Status:</strong> {{ $item->is_active ? 'Aktif' : 'Nonaktif' }}</p>

        <hr>

        <h5>Dokumen</h5>
        <ul class="mb-0">
            @forelse($item->documents as $doc)
                <li>
                    {{ $doc->title }}
                    -
                    <a href="{{ asset('storage/' . $doc->file_path) }}" target="_blank">Lihat PDF</a>
                </li>
            @empty
                <li>Belum ada dokumen.</li>
            @endforelse
        </ul>
    </div>
</div>
@endsection