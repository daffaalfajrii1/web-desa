@extends('public.layouts.app')

@section('title', 'SDGs Desa')

@section('content')
@include('public.infographics._sub-hero', ['title' => 'Tujuan Pembangunan Berkelanjutan', 'subtitle' => 'Nilai skor per tujuan dari modul SDGs admin.', 'kicker' => 'SDGs'])

<section class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-6 lg:py-14">
    @if (! $summary)
        <div class="frontend-empty">Ringkasan SDGs belum diisi dari admin.</div>
    @else
        <div class="mb-10 grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
            <div class="frontend-card p-5">
                <p class="text-xs font-bold uppercase text-slate-500">Rata-rata skor</p>
                <p class="mt-2 text-3xl font-extrabold text-emerald-900">{{ number_format((float) $summary->average_score, 2, ',', '.') }}</p>
                <p class="mt-1 text-xs text-slate-500">Tahun {{ $summary->year }}</p>
            </div>
            <div class="frontend-card p-5">
                <p class="text-xs font-bold uppercase text-slate-500">Baik</p>
                <p class="mt-2 text-3xl font-extrabold">{{ (int) $summary->total_good }}</p>
            </div>
            <div class="frontend-card p-5">
                <p class="text-xs font-bold uppercase text-slate-500">Berkembang</p>
                <p class="mt-2 text-3xl font-extrabold">{{ (int) $summary->total_medium }}</p>
            </div>
            <div class="frontend-card p-5">
                <p class="text-xs font-bold uppercase text-slate-500">Prioritas</p>
                <p class="mt-2 text-3xl font-extrabold">{{ (int) $summary->total_priority }}</p>
            </div>
        </div>

        @if ($goals->isNotEmpty())
            <div class="frontend-card mb-10 p-6">
                <h2 class="text-lg font-bold text-slate-950">Skor per tujuan</h2>
                <div class="mt-6 h-96">
                    <canvas id="chartSdgs"></canvas>
                </div>
            </div>

            <div class="grid gap-5 md:grid-cols-2">
                @foreach ($goals as $gv)
                    <div class="frontend-card p-5">
                        <div class="flex items-start justify-between gap-3">
                            <div>
                                <p class="text-xs font-bold text-emerald-700">Tujuan {{ $gv->goal?->goal_number }}</p>
                                <h3 class="mt-1 font-bold text-slate-950">{{ $gv->goal?->goal_name ?: 'SDGs' }}</h3>
                            </div>
                            <span class="rounded-full bg-emerald-50 px-3 py-1 text-xs font-bold text-emerald-900">{{ $gv->status }}</span>
                        </div>
                        <p class="mt-3 text-3xl font-extrabold text-slate-950">{{ number_format((float) $gv->score, 2, ',', '.') }}</p>
                        @if ($gv->short_description)
                            <p class="mt-2 text-sm text-slate-600">{{ $gv->short_description }}</p>
                        @endif
                    </div>
                @endforeach
            </div>

            @push('scripts')
            <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
            <script>
                document.addEventListener('DOMContentLoaded', function () {
                    var ctx = document.getElementById('chartSdgs');
                    if (!ctx || typeof Chart === 'undefined') return;
                    new Chart(ctx, {
                        type: 'bar',
                        data: {
                            labels: @json($goals->map(fn ($g) => 'Tujuan '.($g->goal?->goal_number ?? '?'))->values()),
                            datasets: [{
                                label: 'Skor',
                                data: @json($goals->pluck('score')->values()),
                                backgroundColor: 'rgba(14, 165, 233, 0.65)',
                                borderRadius: 8,
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: { legend: { display: false } },
                            scales: { x: { ticks: { maxRotation: 45 } }, y: { beginAtZero: true, max: 100 } }
                        }
                    });
                });
            </script>
            @endpush
        @else
            <div class="frontend-empty">Nilai per tujuan belum diisi.</div>
        @endif
    @endif
</section>
@endsection
