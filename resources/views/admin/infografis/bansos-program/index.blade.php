@extends('layouts.admin')

@section('title', 'Program Bansos')
@section('page_title', 'Program Bansos')

@section('content')
<style>
    .bansos-program-card {
        border-radius: 14px;
        border: 1px solid #e9ecef;
        box-shadow: 0 0.125rem 0.25rem rgba(0,0,0,.05);
    }

    .bansos-stat-box {
        border-radius: 12px;
        padding: 1rem;
        color: #fff;
        min-height: 105px;
    }

    .bansos-stat-box .label {
        font-size: .95rem;
        font-weight: 600;
        margin-bottom: .35rem;
    }

    .bansos-stat-box .value {
        font-size: 1.3rem;
        font-weight: 700;
    }
</style>

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center flex-wrap">
        <h3 class="card-title mb-0">Data Program Bansos</h3>
        <a href="{{ route('admin.bansos-program.create') }}" class="btn btn-primary btn-sm">
            <i class="fas fa-plus"></i> Tambah Program
        </a>
    </div>

    <div class="card-body">
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <form method="GET" class="mb-4">
            <div class="row">
                <div class="col-md-3">
                    <label>Tahun</label>
                    <input type="text" name="year" class="form-control" placeholder="Contoh: 2026" value="{{ request('year') }}">
                </div>
                <div class="col-md-4">
                    <label>Cari Program</label>
                    <input type="text" name="search" class="form-control" placeholder="Nama program bansos..." value="{{ request('search') }}">
                </div>
                <div class="col-md-4 d-flex align-items-end">
                    <button class="btn btn-primary mr-2">
                        <i class="fas fa-filter"></i> Filter
                    </button>
                    <a href="{{ route('admin.bansos-program.index') }}" class="btn btn-secondary">
                        Reset
                    </a>
                </div>
            </div>
        </form>

        @forelse($items as $item)
            <div class="card bansos-program-card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center flex-wrap">
                    <div>
                        <h4 class="mb-1 font-weight-bold">{{ $item->name }}</h4>
                        <div class="text-muted">
                            Tahun {{ $item->year }}
                            @if($item->period)
                                • Periode {{ $item->period }}
                            @endif
                        </div>
                    </div>

                    <div class="mt-2 mt-md-0">
                        @if($item->is_active)
                            <span class="badge badge-success">Aktif</span>
                        @else
                            <span class="badge badge-secondary">Nonaktif</span>
                        @endif
                    </div>
                </div>

                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-4 mb-3">
                            <div class="bansos-stat-box bg-info">
                                <div class="label">Kuota</div>
                                <div class="value">{{ number_format($item->quota) }}</div>
                            </div>
                        </div>

                        <div class="col-md-4 mb-3">
                            <div class="bansos-stat-box bg-success">
                                <div class="label">Jumlah Penerima</div>
                                <div class="value">{{ number_format($item->recipients_count ?? 0) }}</div>
                            </div>
                        </div>

                        <div class="col-md-4 mb-3">
                            <div class="bansos-stat-box bg-warning" style="color:#212529;">
                                <div class="label">Sisa Kuota</div>
                                <div class="value">{{ number_format(max(($item->quota ?? 0) - ($item->recipients_count ?? 0), 0)) }}</div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-8">
                            <p class="mb-2">
                                <strong>Deskripsi:</strong><br>
                                {{ $item->description ?: '-' }}
                            </p>
                        </div>

                        <div class="col-md-4">
                            <p class="mb-2"><strong>Tanggal Mulai:</strong> {{ optional($item->start_date)->format('d-m-Y') ?? '-' }}</p>
                            <p class="mb-2"><strong>Tanggal Selesai:</strong> {{ optional($item->end_date)->format('d-m-Y') ?? '-' }}</p>
                        </div>
                    </div>
                </div>

                <div class="card-footer d-flex justify-content-end">
                    <a href="{{ route('admin.bansos-program.edit', $item->id) }}" class="btn btn-warning btn-sm mr-2">
                        <i class="fas fa-edit"></i> Edit
                    </a>

                    <form action="{{ route('admin.bansos-program.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Yakin hapus program bansos ini?')">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-danger btn-sm" type="submit">
                            <i class="fas fa-trash"></i> Hapus
                        </button>
                    </form>
                </div>
            </div>
        @empty
            <div class="alert alert-info mb-0">Belum ada program bansos.</div>
        @endforelse

        <div class="mt-3">
            {{ $items->links() }}
        </div>
    </div>
</div>
@endsection