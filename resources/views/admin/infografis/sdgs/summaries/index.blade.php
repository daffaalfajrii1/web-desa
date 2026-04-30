@extends('layouts.admin')

@section('title', 'SDGS Ringkasan')
@section('page_title', 'SDGS Ringkasan')

@section('content')
<style>
    .sdgs-summary-box {
        border-radius: 12px;
        padding: 1rem;
        color: #fff;
        min-height: 105px;
    }
    .sdgs-summary-box .label {
        font-size: .95rem;
        font-weight: 600;
        margin-bottom: .35rem;
    }
    .sdgs-summary-box .value {
        font-size: 1.2rem;
        font-weight: 700;
    }
</style>

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center flex-wrap">
        <h3 class="card-title mb-0">Ringkasan SDGS Desa</h3>
        <a href="{{ route('admin.sdgs-summaries.create') }}" class="btn btn-primary btn-sm">
            <i class="fas fa-plus"></i> Tambah Ringkasan
        </a>
    </div>

    <div class="card-body">
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <form method="GET" class="mb-4">
            <div class="row">
                <div class="col-md-4">
                    <label>Tahun</label>
                    <input type="text" name="year" class="form-control" value="{{ request('year') }}" placeholder="Filter tahun">
                </div>
                <div class="col-md-4 d-flex align-items-end">
                    <button class="btn btn-primary mr-2">Filter</button>
                    <a href="{{ route('admin.sdgs-summaries.index') }}" class="btn btn-secondary">Reset</a>
                </div>
            </div>
        </form>

        <div class="row">
            @forelse($items as $item)
                <div class="col-md-6 col-xl-4 mb-4">
                    <div class="card h-100">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <strong>SDGS Tahun {{ $item->year }}</strong>
                            @if($item->is_active)
                                <span class="badge badge-success">Aktif</span>
                            @else
                                <span class="badge badge-secondary">Nonaktif</span>
                            @endif
                        </div>

                        <div class="card-body">
                            <div class="row">
                                <div class="col-6 mb-3">
                                    <div class="sdgs-summary-box bg-info">
                                        <div class="label">Rata-rata</div>
                                        <div class="value">{{ number_format($item->average_score, 2, ',', '.') }}</div>
                                    </div>
                                </div>
                                <div class="col-6 mb-3">
                                    <div class="sdgs-summary-box bg-success">
                                        <div class="label">Baik</div>
                                        <div class="value">{{ $item->total_good }}</div>
                                    </div>
                                </div>
                                <div class="col-6 mb-3">
                                    <div class="sdgs-summary-box bg-warning" style="color:#212529">
                                        <div class="label">Berkembang</div>
                                        <div class="value">{{ $item->total_medium }}</div>
                                    </div>
                                </div>
                                <div class="col-6 mb-3">
                                    <div class="sdgs-summary-box bg-danger">
                                        <div class="label">Prioritas</div>
                                        <div class="value">{{ $item->total_priority }}</div>
                                    </div>
                                </div>
                            </div>

                            <p class="mb-0">
                                <strong>Catatan:</strong><br>
                                {{ $item->notes ?: '-' }}
                            </p>
                        </div>

                        <div class="card-footer d-flex justify-content-between">
                            <a href="{{ route('admin.sdgs-summaries.show', $item->id) }}" class="btn btn-info btn-sm">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="{{ route('admin.sdgs-goal-values.index', ['sdgs_summary_id' => $item->id]) }}" class="btn btn-success btn-sm">
                                <i class="fas fa-list"></i>
                            </a>
                            <a href="{{ route('admin.sdgs-summaries.edit', $item->id) }}" class="btn btn-warning btn-sm">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('admin.sdgs-summaries.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Yakin hapus ringkasan SDGS ini?')">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-danger btn-sm" type="submit">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="alert alert-info mb-0">Belum ada ringkasan SDGS.</div>
                </div>
            @endforelse
        </div>

        {{ $items->links() }}
    </div>
</div>
@endsection