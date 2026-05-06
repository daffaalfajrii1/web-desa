@extends('layouts.admin')

@section('title', 'Pengaduan')
@section('page_title', 'Pengaduan')

@section('content')
<style>
    .complaint-stat {
        border-radius: 14px;
        padding: 1rem;
        color: #fff;
        min-height: 108px;
        box-shadow: 0 0.125rem 0.35rem rgba(0,0,0,.08);
        position: relative;
        overflow: hidden;
    }

    .complaint-stat .label {
        font-size: .95rem;
        font-weight: 600;
        opacity: .94;
    }

    .complaint-stat .value {
        font-size: 1.75rem;
        font-weight: 800;
        line-height: 1;
        margin-top: .5rem;
    }

    .complaint-stat .icon {
        position: absolute;
        right: 16px;
        bottom: 10px;
        font-size: 54px;
        opacity: .16;
    }

    .complaint-card {
        border-radius: 14px;
    }

    .complaint-table td {
        vertical-align: top;
    }

    .complaint-subject {
        color: #1f2937;
        font-weight: 700;
        line-height: 1.35;
    }
</style>

<div class="row">
    <div class="col-md-6 col-xl-3 mb-3">
        <div class="complaint-stat bg-primary">
            <div class="label">Masuk</div>
            <div class="value">{{ number_format($summary['masuk']) }}</div>
            <div class="icon"><i class="fas fa-inbox"></i></div>
        </div>
    </div>
    <div class="col-md-6 col-xl-3 mb-3">
        <div class="complaint-stat bg-warning" style="color:#212529">
            <div class="label">Diproses</div>
            <div class="value">{{ number_format($summary['diproses']) }}</div>
            <div class="icon"><i class="fas fa-sync-alt"></i></div>
        </div>
    </div>
    <div class="col-md-6 col-xl-3 mb-3">
        <div class="complaint-stat bg-success">
            <div class="label">Selesai</div>
            <div class="value">{{ number_format($summary['selesai']) }}</div>
            <div class="icon"><i class="fas fa-check-circle"></i></div>
        </div>
    </div>
    <div class="col-md-6 col-xl-3 mb-3">
        <div class="complaint-stat bg-danger">
            <div class="label">Ditolak</div>
            <div class="value">{{ number_format($summary['ditolak']) }}</div>
            <div class="icon"><i class="fas fa-times-circle"></i></div>
        </div>
    </div>
</div>

<div class="card complaint-card">
    <div class="card-header d-flex justify-content-between align-items-center flex-wrap">
        <h3 class="card-title mb-0">Daftar Pengaduan</h3>
    </div>

    <div class="card-body">
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <form method="GET" class="mb-4">
            <div class="row">
                <div class="col-md-3 mb-2">
                    <label>Status</label>
                    <select name="status" class="form-control">
                        <option value="">Semua Status</option>
                        @foreach($statuses as $value => $label)
                            <option value="{{ $value }}" {{ request('status') === $value ? 'selected' : '' }}>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2 mb-2">
                    <label>Dari Tanggal</label>
                    <input type="date" name="date_from" class="form-control" value="{{ request('date_from') }}">
                </div>
                <div class="col-md-2 mb-2">
                    <label>Sampai Tanggal</label>
                    <input type="date" name="date_to" class="form-control" value="{{ request('date_to') }}">
                </div>
                <div class="col-md-3 mb-2">
                    <label>Cari</label>
                    <input type="text" name="search" class="form-control" placeholder="Nama / kode / telepon..." value="{{ request('search') }}">
                </div>
                <div class="col-md-2 mb-2 d-flex align-items-end">
                    <button class="btn btn-primary mr-2">
                        <i class="fas fa-filter"></i> Filter
                    </button>
                    <a href="{{ route('admin.pengaduan.index') }}" class="btn btn-secondary">Reset</a>
                </div>
            </div>
        </form>

        <div class="table-responsive">
            <table class="table table-bordered table-hover complaint-table">
                <thead>
                    <tr>
                        <th style="width:60px">No</th>
                        <th>Kode</th>
                        <th>Pelapor</th>
                        <th>Pengaduan</th>
                        <th>Status</th>
                        <th>Dikirim</th>
                        <th style="width:130px">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($items as $item)
                        <tr>
                            <td>{{ $loop->iteration + ($items->firstItem() ?? 0) - 1 }}</td>
                            <td>
                                <strong>{{ $item->complaint_code }}</strong>
                                @if($item->hasAttachments())
                                    <br><span class="badge badge-info mt-1"><i class="fas fa-paperclip"></i> {{ count($item->attachmentPaths()) }} lampiran</span>
                                @endif
                            </td>
                            <td>
                                <strong>{{ $item->name }}</strong><br>
                                <small class="text-muted">{{ $item->phone }}</small>
                                @if($item->nik)
                                    <br><small class="text-muted">NIK: {{ $item->nik }}</small>
                                @endif
                            </td>
                            <td>
                                <div class="complaint-subject">{{ $item->subject }}</div>
                                <small class="text-muted">{{ \Illuminate\Support\Str::limit(strip_tags($item->complaint_text), 90) }}</small>
                            </td>
                            <td>
                                <span class="badge {{ $item->status_badge_class }}">{{ $item->status_label }}</span>
                            </td>
                            <td>
                                {{ optional($item->submitted_at)->format('d-m-Y H:i') }}
                                @if($item->resolved_at)
                                    <br><small class="text-muted">Selesai: {{ $item->resolved_at->format('d-m-Y H:i') }}</small>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('admin.pengaduan.show', $item->id) }}" class="btn btn-info btn-sm" title="Detail">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('admin.pengaduan.edit', $item->id) }}" class="btn btn-warning btn-sm" title="Ubah Status">
                                    <i class="fas fa-edit"></i>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center">Belum ada pengaduan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-3">
            {{ $items->links() }}
        </div>
    </div>
</div>
@endsection
