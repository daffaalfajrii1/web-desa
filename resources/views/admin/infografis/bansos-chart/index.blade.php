@extends('layouts.admin')

@section('title', 'Chart Bansos')
@section('page_title', 'Chart Bansos')

@section('content')
<style>
    .bansos-summary-box {
        border-radius: 12px;
        padding: 1rem 1.1rem;
        color: #fff;
        min-height: 105px;
        box-shadow: 0 0.125rem 0.25rem rgba(0,0,0,.08);
    }

    .bansos-summary-box .label {
        font-size: .95rem;
        font-weight: 600;
        margin-bottom: .35rem;
    }

    .bansos-summary-box .value {
        font-size: 1.25rem;
        font-weight: 700;
        word-break: break-word;
    }

    .chart-wrap {
        position: relative;
        height: 340px;
    }
</style>

<div class="card">
    <div class="card-header">
        <h3 class="card-title">Visualisasi Bansos</h3>
    </div>

    <div class="card-body">
        <form method="GET" action="{{ route('admin.bansos-chart.index') }}" class="mb-4">
            <div class="row">
                <div class="col-md-4">
                    <label>Filter Program</label>
                    <select name="program_id" class="form-control">
                        <option value="">Semua Program</option>
                        @foreach($programs as $program)
                            <option value="{{ $program->id }}" {{ (string)$programId === (string)$program->id ? 'selected' : '' }}>
                                {{ $program->name }} - {{ $program->year }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4 d-flex align-items-end">
                    <button class="btn btn-primary mr-2">
                        <i class="fas fa-filter"></i> Filter
                    </button>
                    <a href="{{ route('admin.bansos-chart.index') }}" class="btn btn-secondary">Reset</a>
                </div>
            </div>
        </form>

        <div class="row mb-4">
            <div class="col-md-6 col-xl-3 mb-3">
                <div class="bansos-summary-box bg-info">
                    <div class="label">Total Penerima</div>
                    <div class="value">{{ number_format($summary['total_recipients']) }}</div>
                </div>
            </div>

            <div class="col-md-6 col-xl-3 mb-3">
                <div class="bansos-summary-box bg-success">
                    <div class="label">Sudah Diambil</div>
                    <div class="value">{{ number_format($summary['total_distributed']) }}</div>
                </div>
            </div>

            <div class="col-md-6 col-xl-3 mb-3">
                <div class="bansos-summary-box bg-primary">
                    <div class="label">Siap Diambil</div>
                    <div class="value">{{ number_format($summary['total_ready']) }}</div>
                </div>
            </div>

            <div class="col-md-6 col-xl-3 mb-3">
                <div class="bansos-summary-box bg-warning" style="color:#212529;">
                    <div class="label">Total Nominal</div>
                    <div class="value">Rp{{ number_format($summary['total_amount'], 2, ',', '.') }}</div>
                </div>
            </div>
        </div>

        <div class="card card-outline card-info mb-4">
            <div class="card-header">
                <h3 class="card-title">Chart Penerima per Program</h3>
            </div>
            <div class="card-body">
                <div class="chart-wrap">
                    <canvas id="programChart"></canvas>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="card card-outline card-success mb-4">
                    <div class="card-header">
                        <h3 class="card-title">Chart Status Penyaluran</h3>
                    </div>
                    <div class="card-body">
                        <div class="chart-wrap">
                            <canvas id="statusChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card card-outline card-warning mb-4">
                    <div class="card-header">
                        <h3 class="card-title">Chart Penerima per Dusun</h3>
                    </div>
                    <div class="card-body">
                        <div class="chart-wrap">
                            <canvas id="hamletChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const programLabels = @json($programChart->keys()->values());
    const programValues = @json($programChart->values());

    const statusLabels = @json(array_keys($statusChart));
    const statusValues = @json(array_values($statusChart));

    const hamletLabels = @json($hamletChart->keys()->values());
    const hamletValues = @json($hamletChart->values());

    function makeBar(id, labels, values, label, color) {
        const ctx = document.getElementById(id);
        if (!ctx) return;

        new Chart(ctx, {
            type: 'bar',
            data: {
                labels,
                datasets: [{
                    label,
                    data: values,
                    backgroundColor: color,
                    borderColor: color,
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: { beginAtZero: true }
                }
            }
        });
    }

    function makePie(id, labels, values) {
        const ctx = document.getElementById(id);
        if (!ctx) return;

        new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels,
                datasets: [{
                    data: values,
                    backgroundColor: ['#ffc107', '#007bff', '#28a745', '#dc3545', '#6c757d', '#17a2b8']
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                }
            }
        });
    }

    makeBar('programChart', programLabels, programValues, 'Jumlah Penerima', 'rgba(23, 162, 184, 0.75)');
    makePie('statusChart', statusLabels, statusValues);
    makeBar('hamletChart', hamletLabels, hamletValues, 'Jumlah Penerima', 'rgba(255, 193, 7, 0.75)');
</script>
@endpush