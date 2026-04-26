@extends('layouts.admin')
@section('title', 'Detail Permohonan Informasi')
@section('page_title', 'Detail Permohonan Informasi')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between">
        <h3 class="card-title">Detail Permohonan</h3>
        <a href="{{ route('admin.ppid-request.index') }}" class="btn btn-secondary btn-sm">Kembali</a>
    </div>
    <div class="card-body">
        <p><strong>Nama:</strong> {{ $item->name }}</p>
        <p><strong>Instansi:</strong> {{ $item->institution ?? '-' }}</p>
        <p><strong>Email:</strong> {{ $item->email }}</p>
        <p><strong>Telepon:</strong> {{ $item->phone }}</p>
        <p><strong>Status:</strong> {{ ucfirst($item->status) }}</p>
        <p><strong>Tanggal Permintaan:</strong> {{ $item->created_at?->format('d M Y H:i') }}</p>
        <p><strong>Isi Permohonan:</strong></p>
        <div class="border rounded p-3 bg-light">{{ $item->request_content }}</div>

        @if($item->admin_note)
            <hr>
            <p><strong>Catatan Admin:</strong></p>
            <div class="border rounded p-3">{{ $item->admin_note }}</div>
        @endif
    </div>
</div>
@endsection