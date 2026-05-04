@extends('layouts.admin')

@section('title', 'Layanan Mandiri')
@section('page_title', 'Layanan Mandiri')

@section('content')
<style>
    .service-card {
        border-radius: 14px;
        border: 1px solid #e5e7eb;
        box-shadow: 0 0.125rem 0.35rem rgba(0,0,0,.05);
        height: 100%;
    }

    .service-card .service-code {
        font-weight: 800;
        color: #2563eb;
        letter-spacing: .3px;
    }

    .service-title {
        font-size: 1.18rem;
        font-weight: 800;
        color: #1f2937;
        line-height: 1.35;
    }

    .service-meta-box {
        border-radius: 12px;
        background: #f8fafc;
        border: 1px solid #e5e7eb;
        padding: .8rem;
        min-height: 78px;
    }

    .service-meta-box .label {
        color: #6b7280;
        font-size: .83rem;
        font-weight: 700;
        text-transform: uppercase;
    }

    .service-meta-box .value {
        color: #111827;
        font-weight: 800;
        font-size: 1.1rem;
        margin-top: .25rem;
    }

    .requirements-box {
        background: #fff7ed;
        border: 1px solid #fed7aa;
        border-radius: 12px;
        padding: .85rem;
        color: #7c2d12;
        white-space: pre-line;
        min-height: 80px;
    }
</style>

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center flex-wrap">
        <h3 class="card-title mb-0">Master Layanan Mandiri</h3>
        <a href="{{ route('admin.layanan-mandiri.create') }}" class="btn btn-primary btn-sm">
            <i class="fas fa-plus"></i> Tambah Layanan
        </a>
    </div>
    <div class="card-body">
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <form method="GET" class="mb-4">
            <div class="row">
                <div class="col-md-4 mb-2">
                    <label>Cari Layanan</label>
                    <input type="text" name="search" class="form-control" placeholder="Nama atau kode layanan..." value="{{ request('search') }}">
                </div>
                <div class="col-md-3 mb-2">
                    <label>Status</label>
                    <select name="status" class="form-control">
                        <option value="">Semua Status</option>
                        <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Aktif</option>
                        <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Nonaktif</option>
                    </select>
                </div>
                <div class="col-md-4 mb-2 d-flex align-items-end">
                    <button class="btn btn-primary mr-2">
                        <i class="fas fa-filter"></i> Filter
                    </button>
                    <a href="{{ route('admin.layanan-mandiri.index') }}" class="btn btn-secondary">Reset</a>
                </div>
            </div>
        </form>

        <div class="row">
            @forelse($items as $item)
                <div class="col-lg-6 col-xl-4 mb-4">
                    <div class="card service-card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start mb-3">
                                <div>
                                    <div class="service-code">{{ $item->service_code }}</div>
                                    <div class="service-title">{{ $item->service_name }}</div>
                                </div>
                                @if($item->is_active)
                                    <span class="badge badge-success p-2">Aktif</span>
                                @else
                                    <span class="badge badge-secondary p-2">Nonaktif</span>
                                @endif
                            </div>

                            <p class="text-muted mb-3">
                                {{ \Illuminate\Support\Str::limit($item->description ?: 'Belum ada deskripsi layanan.', 130) }}
                            </p>

                            <div class="row mb-3">
                                <div class="col-4">
                                    <div class="service-meta-box">
                                        <div class="label">Jumlah Field</div>
                                        <div class="value">{{ number_format($item->fields_count) }}</div>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="service-meta-box">
                                        <div class="label">Pengajuan</div>
                                        <div class="value">{{ number_format($item->submissions_count) }}</div>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="service-meta-box">
                                        <div class="label">Proses</div>
                                        <div class="value">{{ number_format($item->pending_submissions_count) }}</div>
                                    </div>
                                </div>
                            </div>

                            <div class="requirements-box mb-3">
                                <strong>Syarat:</strong><br>
                                {{ $item->requirements ?: 'Belum ada syarat layanan.' }}
                            </div>
                        </div>

                        <div class="card-footer bg-white d-flex flex-wrap">
                            <a href="{{ route('admin.layanan-mandiri.submissions.index', $item->id) }}" class="btn btn-info btn-sm mr-2 mb-2">
                                <i class="fas fa-inbox"></i> Pengajuan
                            </a>
                            <a href="{{ route('admin.layanan-mandiri.fields.index', $item->id) }}" class="btn btn-success btn-sm mr-2 mb-2">
                                <i class="fas fa-list"></i> Kelola Field
                            </a>
                            <a href="{{ route('admin.layanan-mandiri.edit', $item->id) }}" class="btn btn-warning btn-sm mr-2 mb-2">
                                <i class="fas fa-edit"></i> Edit
                            </a>
                            <form action="{{ route('admin.layanan-mandiri.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Yakin hapus layanan ini? Semua field ikut terhapus.')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm mb-2">
                                    <i class="fas fa-trash"></i> Hapus
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="alert alert-info mb-0">Belum ada layanan mandiri.</div>
                </div>
            @endforelse
        </div>

        <div class="mt-3">
            {{ $items->links() }}
        </div>
    </div>
</div>
@endsection
