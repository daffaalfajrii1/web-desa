@extends('layouts.admin')

@section('title', 'Chart APBDes')
@section('page_title', 'Chart APBDes')

@section('content')
<style>
    .apbdes-summary-box {
        border-radius: 12px;
        padding: 1rem 1.1rem;
        color: #fff;
        min-height: 110px;
        box-shadow: 0 0.125rem 0.25rem rgba(0,0,0,.08);
    }
    .apbdes-summary-box .label {
        font-size: .95rem;
        font-weight: 600;
        margin-bottom: .35rem;
    }
    .apbdes-summary-box .value {
        font-size: 1.2rem;
        font-weight: 700;
        line-height: 1.4;
        word-break: break-word;
    }
    .chart-card {
        border-radius: 14px;
    }
    .chart-wrap {
        position: relative;
        height: 340px;
    }
</style>

@php
    $fmt = function ($v) {
        return $v < 0
            ? '-Rp' . number_format(abs($v), 2, ',', '.')
            : 'Rp' . number_format($v, 2, ',', '.');
    };
@endphp

<div class="card chart-card">
    <div class="card-header d-flex justify-content-between align-items-center flex-wrap">
        <h3 class="card-title mb-0">Visualisasi APBDes</h3>
        <div class="mt-2 mt-md-0">
            <a href="{{ route('admin.apbdes.index') }}" class="btn btn-secondary btn-sm">
                <i class="fas fa-arrow-left"></i> Kembali ke Data
            </a>
        </div>
    </div>

    <div class="card-body">
        <form method="GET" action="{{ route('admin.apbdes.chart-view') }}" class="mb-4">
            <div class="row">
                <div class="col-md-4">
                    <label>Filter Tahun</label>
                    <input type="text" name="year" class="form-control" placeholder="Kosongkan untuk semua tahun" value="{{ $year }}">
                </div>
                <div class="col-md-4 d-flex align-items-end">
                    <button class="btn btn-primary mr-2">
                        <i class="fas fa-filter"></i> Filter
                    </button>
                    <a href="{{ route('admin.apbdes.chart-view') }}" class="btn btn-secondary">
                        Reset
                    </a>
                </div>
            </div>
        </form>

        <div class="row mb-4">
            <div class="col-md-6 col-xl-3 mb-3">
                <div class="apbdes-summary-box bg-info">
                    <div class="label">Total Pendapatan</div>
                    <div class="value">{{ $fmt($summary['pendapatan']) }}</div>
                </div>
            </div>
            <div class="col-md-6 col-xl-3 mb-3">
                <div class="apbdes-summary-box bg-danger">
                    <div class="label">Total Belanja</div>
                    <div class="value">{{ $fmt($summary['belanja']) }}</div>
                </div>
            </div>
            <div class="col-md-6 col-xl-3 mb-3">
                <div class="apbdes-summary-box bg-warning">
                    <div class="label">Total Pemb. Netto</div>
                    <div class="value" style="color:#212529">{{ $fmt($summary['pembiayaan_netto']) }}</div>
                </div>
            </div>
            <div class="col-md-6 col-xl-3 mb-3">
                <div class="apbdes-summary-box bg-success">
                    <div class="label">Total SILPA</div>
                    <div class="value">{{ $fmt($summary['silpa']) }}</div>
                </div>
            </div>
        </div>

        <div class="card card-outline card-primary mb-4">
            <div class="card-header">
                <h3 class="card-title">Pendapatan vs Belanja</h3>
            </div>
            <div class="card-body">
                <div class="chart-wrap">
                    <canvas id="chartPendapatanBelanja"></canvas>
                </div>
            </div>
        </div>

        <div class="card card-outline card-warning mb-4">
            <div class="card-header">
                <h3 class="card-title">Pembiayaan</h3>
            </div>
            <div class="card-body">
                <div class="chart-wrap">
                    <canvas id="chartPembiayaan"></canvas>
                </div>
            </div>
        </div>

        <div class="card card-outline card-success mb-4">
            <div class="card-header">
                <h3 class="card-title">Surplus / Defisit, Netto, SILPA</h3>
            </div>
            <div class="card-body">
                <div class="chart-wrap">
                    <canvas id="chartHasil"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const chartData = @json($chartData);

    function rupiahTick(value) {
        return 'Rp' + Number(value).toLocaleString('id-ID');
    }

    function makeBarChart(id, datasets) {
        const el = document.getElementById(id);
        if (!el) return;

        new Chart(el, {
            type: 'bar',
            data: {
                labels: chartData.labels,
                datasets: datasets
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: true }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: rupiahTick
                        }
                    }
                }
            }
        });
    }

    function makeLineChart(id, datasets) {
        const el = document.getElementById(id);
        if (!el) return;

        new Chart(el, {
            type: 'line',
            data: {
                labels: chartData.labels,
                datasets: datasets
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: true }
                },
                scales: {
                    y: {
                        ticks: {
                            callback: rupiahTick
                        }
                    }
                }
            }
        });
    }

    makeBarChart('chartPendapatanBelanja', [
        {
            label: 'Pendapatan',
            data: chartData.pendapatan,
            backgroundColor: 'rgba(23, 162, 184, 0.75)',
            borderColor: 'rgba(23, 162, 184, 1)',
            borderWidth: 1
        },
        {
            label: 'Belanja',
            data: chartData.belanja,
            backgroundColor: 'rgba(220, 53, 69, 0.75)',
            borderColor: 'rgba(220, 53, 69, 1)',
            borderWidth: 1
        }
    ]);

    makeBarChart('chartPembiayaan', [
        {
            label: 'Pemb. Penerimaan',
            data: chartData.pembiayaan_penerimaan,
            backgroundColor: 'rgba(255, 193, 7, 0.75)',
            borderColor: 'rgba(255, 193, 7, 1)',
            borderWidth: 1
        },
        {
            label: 'Pemb. Pengeluaran',
            data: chartData.pembiayaan_pengeluaran,
            backgroundColor: 'rgba(108, 117, 125, 0.75)',
            borderColor: 'rgba(108, 117, 125, 1)',
            borderWidth: 1
        }
    ]);

    makeLineChart('chartHasil', [
        {
            label: 'Surplus / Defisit',
            data: chartData.surplus_defisit,
            borderColor: 'rgba(0, 123, 255, 1)',
            backgroundColor: 'rgba(0, 123, 255, 0.2)',
            tension: 0.2,
            fill: false
        },
        {
            label: 'Pembiayaan Netto',
            data: chartData.pembiayaan_netto,
            borderColor: 'rgba(255, 193, 7, 1)',
            backgroundColor: 'rgba(255, 193, 7, 0.2)',
            tension: 0.2,
            fill: false
        },
        {
            label: 'SILPA',
            data: chartData.silpa,
            borderColor: 'rgba(40, 167, 69, 1)',
            backgroundColor: 'rgba(40, 167, 69, 0.2)',
            tension: 0.2,
            fill: false
        }
    ]);
</script>
@endpush