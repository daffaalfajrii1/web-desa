@extends('layouts.admin')
@section('title', 'Detail Dokumen PPID')
@section('page_title', 'Detail Dokumen PPID')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between">
        <h3 class="card-title">{{ $item->title }}</h3>
        <a href="{{ route('admin.ppid-document.index') }}" class="btn btn-secondary btn-sm">Kembali</a>
    </div>
    <div class="card-body">
        <p><strong>Section:</strong> {{ $item->section?->title ?? '-' }}</p>
        <p><strong>Jenis:</strong> {{ str_replace('_', ' ', ucfirst($item->section?->type ?? '-')) }}</p>
        <p><strong>Urutan:</strong> {{ $item->sort_order }}</p>
        <p><strong>Status:</strong> {{ $item->is_active ? 'Aktif' : 'Nonaktif' }}</p>

        <a href="{{ asset('storage/' . $item->file_path) }}" target="_blank" class="btn btn-danger">
            <i class="fas fa-file-pdf"></i> Buka PDF
        </a>
    </div>
</div>
@endsection