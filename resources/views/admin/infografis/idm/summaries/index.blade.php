@extends('layouts.admin')

@section('title', 'IDM')
@section('page_title', 'IDM')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center flex-wrap">
        <h3 class="card-title mb-0">Ringkasan IDM</h3>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.idm-summaries.create') }}" class="btn btn-primary btn-sm">
                <i class="fas fa-plus"></i> Tambah Ringkasan
            </a>
            <a href="{{ route('admin.idm-indicators.index') }}" class="btn btn-success btn-sm">
                <i class="fas fa-table"></i> Kelola Indikator
            </a>
        </div>
    </div>

    <div class="card-body">
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <form method="GET" class="mb-4">
            <div class="row">
                <div class="col-md-4">
                    <label>Tahun</label>
                    <input type="text" name="year" value="{{ request('year') }}" class="form-control" placeholder="Filter tahun">
                </div>
                <div class="col-md-4 d-flex align-items-end">
                    <button class="btn btn-primary mr-2">Filter</button>
                    <a href="{{ route('admin.idm-summaries.index') }}" class="btn btn-secondary">Reset</a>
                </div>
            </div>
        </form>

        <div class="row">
            @forelse($items as $item)
                <div class="col-md-6 col-xl-4">
                    <div class="card card-outline card-success h-100">
                        <div class="card-header">
                            <div class="d-flex justify-content-between align-items-center">
                                <strong>IDM Tahun {{ $item->year }}</strong>
                                <span class="badge badge-{{ $item->idm_status === 'Mandiri' ? 'success' : ($item->idm_status === 'Maju' ? 'primary' : 'warning') }}">
                                    {{ $item->idm_status }}
                                </span>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="mb-2"><strong>Skor IDM:</strong> {{ number_format($item->idm_score, 4) }}</div>
                            <div class="mb-2"><strong>IKS:</strong> {{ number_format($item->iks_score, 4) }}</div>
                            <div class="mb-2"><strong>IKE:</strong> {{ number_format($item->ike_score, 4) }}</div>
                            <div class="mb-2"><strong>IKL:</strong> {{ number_format($item->ikl_score, 4) }}</div>
                            <div class="mb-2"><strong>Target:</strong> {{ $item->target_status }}</div>
                            <div class="mb-2"><strong>Minimal:</strong> {{ number_format($item->minimal_target_score, 4) }}</div>
                            <div class="mb-2"><strong>Penambahan:</strong> {{ number_format($item->additional_score_needed, 4) }}</div>
                        </div>
                        <div class="card-footer d-flex justify-content-between">
                            <a href="{{ route('admin.idm-summaries.show', $item->id) }}" class="btn btn-info btn-sm">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="{{ route('admin.idm-summaries.edit', $item->id) }}" class="btn btn-warning btn-sm">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('admin.idm-summaries.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Yakin hapus data?')">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-danger btn-sm" type="submit">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                            <a href="{{ route('admin.idm-indicators.index', ['idm_summary_id' => $item->id]) }}" class="btn btn-success btn-sm">
                                <i class="fas fa-list"></i> Indikator
                            </a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="alert alert-warning mb-0">Belum ada ringkasan IDM.</div>
                </div>
            @endforelse
        </div>

        <div class="mt-3">
            {{ $items->links() }}
        </div>
    </div>
</div>
@endsection