@extends('layouts.admin')

@section('title', 'Detail Pengaduan')
@section('page_title', 'Detail Pengaduan')

@section('content')
<style>
    .detail-panel {
        border: 1px solid #e5e7eb;
        border-radius: 14px;
        background: #fff;
        box-shadow: 0 0.125rem 0.35rem rgba(0,0,0,.04);
    }

    .detail-row {
        border-bottom: 1px solid #eef1f4;
        padding: .85rem 0;
    }

    .detail-row:last-child {
        border-bottom: 0;
    }

    .detail-label {
        color: #6b7280;
        font-weight: 700;
        font-size: .86rem;
        text-transform: uppercase;
        letter-spacing: .35px;
    }

    .complaint-body {
        white-space: pre-line;
        line-height: 1.7;
        color: #1f2937;
    }
</style>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

<div class="row">
    <div class="col-lg-8 mb-4">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center flex-wrap">
                <div>
                    <h3 class="card-title mb-0">{{ $item->subject }}</h3>
                    <div class="text-muted mt-1">{{ $item->complaint_code }}</div>
                </div>
                <span class="badge {{ $item->status_badge_class }} p-2">{{ $item->status_label }}</span>
            </div>
            <div class="card-body">
                <div class="complaint-body">{{ $item->complaint_text }}</div>

                @if($item->hasAttachments())
                    <div class="mt-4 pt-3 border-top">
                        <div class="detail-label mb-3">Lampiran</div>
                        <div class="d-flex flex-column gap-2">
                            @foreach($item->attachmentPaths() as $path)
                                @php
                                    $ext = strtolower(pathinfo($path, PATHINFO_EXTENSION) ?: '');
                                    $isPdf = $ext === 'pdf';
                                    $url = asset('storage/' . $path);
                                    $label = basename($path);
                                @endphp
                                <div class="d-flex align-items-center flex-wrap gap-2">
                                    <a href="{{ $url }}" target="_blank" rel="noopener" class="btn btn-outline-info btn-sm">
                                        <i class="fas {{ $isPdf ? 'fa-file-pdf' : 'fa-image' }}"></i>
                                        {{ $isPdf ? 'Buka PDF' : 'Lihat gambar' }} — {{ \Illuminate\Support\Str::limit($label, 40) }}
                                    </a>
                                    <a href="{{ $url }}" download class="btn btn-light btn-sm border" title="Unduh"><i class="fas fa-download"></i></a>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h3 class="card-title mb-0">Catatan Admin</h3>
            </div>
            <div class="card-body">
                <div class="complaint-body">{{ $item->admin_note ?: 'Belum ada catatan admin.' }}</div>
            </div>
        </div>
    </div>

    <div class="col-lg-4 mb-4">
        <div class="detail-panel p-3">
            <div class="detail-row">
                <div class="detail-label">Pelapor</div>
                <div><strong>{{ $item->name }}</strong></div>
                <div class="text-muted">{{ $item->phone }}</div>
            </div>
            <div class="detail-row">
                <div class="detail-label">NIK</div>
                <div>{{ $item->nik ?: '-' }}</div>
            </div>
            <div class="detail-row">
                <div class="detail-label">Email</div>
                <div>{{ $item->email ?: '-' }}</div>
            </div>
            <div class="detail-row">
                <div class="detail-label">Alamat</div>
                <div>{{ $item->address ?: '-' }}</div>
            </div>
            <div class="detail-row">
                <div class="detail-label">Tanggal Masuk</div>
                <div>{{ optional($item->submitted_at)->format('d-m-Y H:i') }}</div>
            </div>
            <div class="detail-row">
                <div class="detail-label">Tanggal Selesai</div>
                <div>{{ optional($item->resolved_at)->format('d-m-Y H:i') ?: '-' }}</div>
            </div>
        </div>

        <div class="mt-3 d-flex flex-wrap">
            <a href="{{ route('admin.pengaduan.index') }}" class="btn btn-secondary mr-2 mb-2">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
            <a href="{{ route('admin.pengaduan.edit', $item->id) }}" class="btn btn-warning mb-2">
                <i class="fas fa-edit"></i> Ubah Status
            </a>
        </div>
    </div>
</div>
@endsection
