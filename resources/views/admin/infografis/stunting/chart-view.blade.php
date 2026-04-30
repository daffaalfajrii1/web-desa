@extends('layouts.admin')

@section('title', 'Chart Stunting')
@section('page_title', 'Chart Stunting')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Visualisasi Data Stunting</h3>
    </div>

    <div class="card-body">
        <form method="GET" action="{{ route('admin.stunting-chart.index') }}" class="mb-4">
            <div class="row">
                <div class="col-md-3">
                    <label>Tahun</label>
                    <input type="text" name="year" class="form-control" value="{{ request('year') }}">
                </div>
                <div class="col-md-3">
                    <label>Dusun</label>
                    <select name="hamlet_id" class="form-control">
                        <option value="">Semua Dusun</option>
                        @foreach($hamlets as $hamlet)
                            <option value="{{ $hamlet->id }}" {{ (string)request('hamlet_id') === (string)$hamlet->id ? 'selected' : '' }}>
                                {{ $hamlet->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3 d-flex align-items-end">
                    <button class="btn btn-primary mr-2">Filter</button>
                    <a href="{{ route('admin.stunting-chart.index') }}" class="btn btn-secondary">Reset</a>
                </div>
            </div>
        </form>

        <div class="row mb-4">
            <div class="col-md-3">
                <div class="small-box bg-info">
                    <div class="inner">
                        <h3>{{ $summary['total'] }}</h3>
                        <p>Total Data</p>
                    </div>
                    <div class="icon"><i class="fas fa-child"></i></div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="small-box bg-danger">
                    <div class="inner">
                        <h3>{{ $summary['stunting'] }}</h3>
                        <p>Stunting</p>
                    </div>
                    <div class="icon"><i class="fas fa-exclamation-triangle"></i></div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="small-box bg-success">
                    <div class="inner">
                        <h3>{{ $summary['normal'] }}</h3>
                        <p>Normal</p>
                    </div>
                    <div class="icon"><i class="fas fa-check-circle"></i></div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="small-box bg-warning">
                    <div class="inner">
                        <h3>{{ $summary['berisiko'] }}</h3>
                        <p>Berisiko</p>
                    </div>
                    <div class="icon"><i class="fas fa-exclamation-circle"></i></div>
                </div>
            </div>
        </div>

        <div class="card card-outline card-danger mb-4">
            <div class="card-header"><h3 class="card-title">Chart Status Stunting</h3></div>
            <div class="card-body"><canvas id="statusChart" height="120"></canvas></div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="card card-outline card-info mb-4">
                    <div class="card-header"><h3 class="card-title">Chart per Dusun</h3></div>
                    <div class="card-body"><canvas id="hamletChart" height="160"></canvas></div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card card-outline card-primary mb-4">
                    <div class="card-header"><h3 class="card-title">Chart Jenis Kelamin</h3></div>
                    <div class="card-body"><canvas id="genderChart" height="160"></canvas></div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const statusLabels = @json(array_keys($statusChart));
    const statusValues = @json(array_values($statusChart));

    const hamletLabels = @json($hamletChart->keys()->values());
    const hamletValues = @json($hamletChart->values());

    const genderLabels = @json(array_keys($genderChart));
    const genderValues = @json(array_values($genderChart));

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

    function makePie(id, labels, values, colors) {
        const ctx = document.getElementById(id);
        if (!ctx) return;

        new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels,
                datasets: [{
                    data: values,
                    backgroundColor: colors
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

    makePie('statusChart', statusLabels, statusValues, ['#28a745', '#dc3545', '#ffc107']);
    makeBar('hamletChart', hamletLabels, hamletValues, 'Jumlah Anak', 'rgba(23, 162, 184, 0.75)');
    makePie('genderChart', genderLabels, genderValues, ['#007bff', '#e83e8c']);
</script>
@endpush