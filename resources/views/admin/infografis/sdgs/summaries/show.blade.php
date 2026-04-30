@extends('layouts.admin')

@section('title', 'Detail SDGS')
@section('page_title', 'Detail SDGS')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h3 class="card-title mb-0">Detail SDGS Tahun {{ $item->year }}</h3>
        <a href="{{ route('admin.sdgs-goal-values.index', ['sdgs_summary_id' => $item->id]) }}" class="btn btn-success btn-sm">
            <i class="fas fa-list"></i> Nilai Tujuan
        </a>
    </div>

    <div class="card-body">
        <div class="row">
            <div class="col-md-3 mb-3">
                <div class="small-box bg-info">
                    <div class="inner">
                        <h3>{{ number_format($item->average_score, 2, ',', '.') }}</h3>
                        <p>Rata-rata Skor</p>
                    </div>
                    <div class="icon"><i class="fas fa-chart-line"></i></div>
                </div>
            </div>

            <div class="col-md-3 mb-3">
                <div class="small-box bg-success">
                    <div class="inner">
                        <h3>{{ $item->total_good }}</h3>
                        <p>Tujuan Baik</p>
                    </div>
                    <div class="icon"><i class="fas fa-check-circle"></i></div>
                </div>
            </div>

            <div class="col-md-3 mb-3">
                <div class="small-box bg-warning">
                    <div class="inner">
                        <h3>{{ $item->total_medium }}</h3>
                        <p>Berkembang</p>
                    </div>
                    <div class="icon"><i class="fas fa-exclamation-circle"></i></div>
                </div>
            </div>

            <div class="col-md-3 mb-3">
                <div class="small-box bg-danger">
                    <div class="inner">
                        <h3>{{ $item->total_priority }}</h3>
                        <p>Prioritas</p>
                    </div>
                    <div class="icon"><i class="fas fa-bullseye"></i></div>
                </div>
            </div>
        </div>

        <p><strong>Catatan:</strong><br>{{ $item->notes ?: '-' }}</p>
    </div>
</div>
@endsection