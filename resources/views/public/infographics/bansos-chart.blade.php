@extends('public.layouts.app')

@section('title', 'Chart Bansos')

@section('content')
@include('public.infographics._sub-hero', ['title' => 'Visualisasi Bansos', 'subtitle' => 'Ringkasan grafis dari data program dan penyaluran.', 'kicker' => 'Bansos'])

<section class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-6 lg:py-14">
    @if ($perProgram->isEmpty() && $distribution->isEmpty())
        <div class="frontend-empty">Data grafik belum tersedia.</div>
    @else
        <div class="grid gap-8 lg:grid-cols-2">
            <div class="frontend-card p-6">
                <h2 class="text-lg font-bold text-slate-950">Penerima per program</h2>
                <div class="mt-6 h-72">
                    <canvas id="chartBansosProgram"></canvas>
                </div>
            </div>
            <div class="frontend-card p-6">
                <h2 class="text-lg font-bold text-slate-950">Distribusi status penyaluran</h2>
                <div class="mt-6 h-72">
                    <canvas id="chartBansosDist"></canvas>
                </div>
            </div>
        </div>

        @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                var progEl = document.getElementById('chartBansosProgram');
                var distEl = document.getElementById('chartBansosDist');
                if (progEl && typeof Chart !== 'undefined') {
                    new Chart(progEl, {
                        type: 'bar',
                        data: {
                            labels: @json($perProgram->pluck('name')),
                            datasets: [{
                                label: 'Penerima',
                                data: @json($perProgram->pluck('recipients_count')),
                                backgroundColor: 'rgba(5, 150, 105, 0.7)',
                                borderRadius: 8,
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: { legend: { display: false } },
                            scales: { x: { ticks: { maxRotation: 45 } }, y: { beginAtZero: true } }
                        }
                    });
                }
                if (distEl && typeof Chart !== 'undefined') {
                    new Chart(distEl, {
                        type: 'pie',
                        data: {
                            labels: @json($distribution->keys()),
                            datasets: [{
                                data: @json($distribution->values()),
                                backgroundColor: ['rgba(5,150,105,.75)', 'rgba(234,179,8,.8)', 'rgba(59,130,246,.75)', 'rgba(244,63,94,.75)', 'rgba(100,116,139,.75)'],
                            }]
                        },
                        options: { responsive: true, maintainAspectRatio: false }
                    });
                }
            });
        </script>
        @endpush
    @endif
</section>
@endsection
