@extends('layouts.admin')

@section('title', 'Detail Pegawai')
@section('page_title', 'Detail Pegawai')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h3 class="card-title mb-0">{{ $item->name }}</h3>
        <div>
            <a href="{{ route('admin.pegawai.edit', $item->id) }}" class="btn btn-warning btn-sm">
                <i class="fas fa-edit"></i> Edit
            </a>
            <a href="{{ route('admin.pegawai.index') }}" class="btn btn-secondary btn-sm">
                Kembali
            </a>
        </div>
    </div>

    <div class="card-body">
        @if($item->photo)
            <div class="mb-4">
                <img src="{{ asset('storage/' . $item->photo) }}" alt="{{ $item->name }}" style="max-width: 220px;" class="img-fluid rounded">
            </div>
        @endif

        <div class="row">
            <div class="col-md-6">
                <p><strong>Nama:</strong> {{ $item->name }}</p>
                <p><strong>Jabatan:</strong> {{ $item->position }}</p>
                <p><strong>Master Jabatan:</strong> {{ $item->employeePosition?->name ?? '-' }}</p>
                <p><strong>Jenis Jabatan:</strong> {{ $item->position_type ?? '-' }}</p>
                <p><strong>NIP / NIK:</strong> {{ $item->nip ?? '-' }}</p>
                <p><strong>Email:</strong> {{ $item->email ?? '-' }}</p>
                <p><strong>Telepon:</strong> {{ $item->phone ?? '-' }}</p>
            </div>

            <div class="col-md-6">
                <p><strong>Terkait Akun:</strong> {{ $item->user?->name ?? 'Tidak terhubung' }}</p>
                <p><strong>Urutan Tampil:</strong> {{ $item->sort_order }}</p>
                <p><strong>PIN Absensi:</strong> {{ $item->pin_absensi ?? $item->attendance_pin ?? '-' }}</p>
                <p><strong>Status:</strong>
                    @if($item->is_active)
                        <span class="badge badge-success">Aktif</span>
                    @else
                        <span class="badge badge-danger">Nonaktif</span>
                    @endif
                </p>
            </div>
        </div>

        <hr>

        <h5>Media Sosial</h5>
        <p><strong>Facebook:</strong> {{ $item->facebook ?? '-' }}</p>
        <p><strong>Instagram:</strong> {{ $item->instagram ?? '-' }}</p>
        <p><strong>Twitter/X:</strong> {{ $item->twitter ?? '-' }}</p>
        <p><strong>YouTube:</strong> {{ $item->youtube ?? '-' }}</p>
        <p><strong>WhatsApp:</strong> {{ $item->whatsapp ?? '-' }}</p>
        <p><strong>Telegram:</strong> {{ $item->telegram ?? '-' }}</p>
    </div>
</div>
@endsection
