@extends('layouts.admin')

@section('title', 'Chart Statistik Penduduk')
@section('page_title', 'Chart Statistik Penduduk')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h3 class="card-title mb-0">Visualisasi Statistik Penduduk</h3>
        <a href="{{ route('admin.population-stats.index') }}" class="btn btn-secondary btn-sm">
            <i class="fas fa-arrow-left"></i> Kembali ke Data
        </a>
    </div>

    <div class="card-body">
        <form method="GET" action="{{ route('admin.population-stats.chart-view') }}" class="mb-4">
            <div class="row">
                <div class="col-md-3">
                    <label>Tahun</label>
                    <input type="text" name="year" class="form-control" placeholder="Contoh: 2026" value="{{ $selectedYear }}">
                </div>
                <div class="col-md-4">
                    <label>Dusun</label>
                    <select name="hamlet_id" class="form-control">
                        <option value="">Semua Dusun</option>
                        @foreach($hamlets as $hamlet)
                            <option value="{{ $hamlet->id }}" {{ (string)$selectedHamlet === (string)$hamlet->id ? 'selected' : '' }}>
                                {{ $hamlet->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4 d-flex align-items-end">
                    <button class="btn btn-primary mr-2">
                        <i class="fas fa-filter"></i> Filter
                    </button>
                    <a href="{{ route('admin.population-stats.chart-view') }}" class="btn btn-secondary">
                        Reset
                    </a>
                </div>
            </div>
        </form>

        <div class="row mb-4">
            <div class="col-md-3">
                <div class="small-box bg-info">
                    <div class="inner">
                        <h3>{{ number_format($summaryCards['total_population']) }}</h3>
                        <p>Total Penduduk</p>
                    </div>
                    <div class="icon"><i class="fas fa-users"></i></div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="small-box bg-success">
                    <div class="inner">
                        <h3>{{ number_format($summaryCards['total_kk']) }}</h3>
                        <p>Kepala Keluarga / KK</p>
                    </div>
                    <div class="icon"><i class="fas fa-home"></i></div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="small-box bg-primary">
                    <div class="inner">
                        <h3>{{ number_format($summaryCards['male_count']) }}</h3>
                        <p>Laki-laki</p>
                    </div>
                    <div class="icon"><i class="fas fa-male"></i></div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="small-box bg-pink" style="background:#e83e8c;color:white;">
                    <div class="inner">
                        <h3>{{ number_format($summaryCards['female_count']) }}</h3>
                        <p>Perempuan</p>
                    </div>
                    <div class="icon"><i class="fas fa-female"></i></div>
                </div>
            </div>
        </div>

        <div class="card card-outline card-primary mb-4">
            <div class="card-header">
                <h3 class="card-title">Chart Umur</h3>
            </div>
            <div class="card-body">
                <canvas id="chartUmur" height="110"></canvas>
            </div>
        </div>

        <div class="card card-outline card-success mb-4">
            <div class="card-header">
                <h3 class="card-title">Chart Pendidikan</h3>
            </div>
            <div class="card-body">
                <canvas id="chartPendidikan" height="120"></canvas>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="card card-outline card-warning mb-4">
                    <div class="card-header">
                        <h3 class="card-title">Chart Wajib Pilih</h3>
                    </div>
                    <div class="card-body">
                        <canvas id="chartWajibPilih" height="140"></canvas>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card card-outline card-danger mb-4">
                    <div class="card-header">
                        <h3 class="card-title">Chart Perkawinan</h3>
                    </div>
                    <div class="card-body">
                        <canvas id="chartPerkawinan" height="140"></canvas>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card card-outline card-info mb-4">
                    <div class="card-header">
                        <h3 class="card-title">Chart Agama</h3>
                    </div>
                    <div class="card-body">
                        <canvas id="chartAgama" height="140"></canvas>
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
    const chartData = @json($chartData);

    function makeBarChart(id, labels, values, label, color = 'rgba(54, 162, 235, 0.7)') {
        const el = document.getElementById(id);
        if (!el) return;

        new Chart(el, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    label: label,
                    data: values,
                    backgroundColor: color,
                    borderColor: color,
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: true }
                },
                scales: {
                    y: { beginAtZero: true }
                }
            }
        });
    }

    function makeLineChart(id, labels, values, label) {
        const el = document.getElementById(id);
        if (!el) return;

        new Chart(el, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: label,
                    data: values,
                    fill: false,
                    borderColor: 'rgba(255, 159, 64, 1)',
                    backgroundColor: 'rgba(255, 159, 64, 0.5)',
                    tension: 0.2
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: true }
                },
                scales: {
                    y: { beginAtZero: true }
                }
            }
        });
    }

    function makePieChart(id, labels, values, label) {
        const el = document.getElementById(id);
        if (!el) return;

        new Chart(el, {
            type: 'doughnut',
            data: {
                labels: labels,
                datasets: [{
                    label: label,
                    data: values,
                    backgroundColor: [
                        '#4CAF50',
                        '#2196F3',
                        '#FF9800',
                        '#E91E63',
                        '#9C27B0',
                        '#795548',
                        '#607D8B',
                        '#00BCD4',
                        '#8BC34A',
                        '#FFC107'
                    ]
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { position: 'bottom' }
                }
            }
        });
    }

    makeBarChart(
        'chartUmur',
        chartData.umur.labels,
        chartData.umur.values,
        'Jumlah Penduduk per Umur',
        'rgba(54, 162, 235, 0.7)'
    );

    makeBarChart(
        'chartPendidikan',
        chartData.pendidikan.labels,
        chartData.pendidikan.values,
        'Jumlah Penduduk per Pendidikan',
        'rgba(40, 167, 69, 0.7)'
    );

    makeLineChart(
        'chartWajibPilih',
        chartData.wajib_pilih.labels,
        chartData.wajib_pilih.values,
        'Jumlah Wajib Pilih'
    );

    makePieChart(
        'chartPerkawinan',
        chartData.perkawinan.labels,
        chartData.perkawinan.values,
        'Status Perkawinan'
    );

    makePieChart(
        'chartAgama',
        chartData.agama.labels,
        chartData.agama.values,
        'Agama'
    );
</script>
@endpush