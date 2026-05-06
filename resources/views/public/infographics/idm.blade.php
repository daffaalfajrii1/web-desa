@extends('public.layouts.app')

@section('title', 'Indeks Desa Membangun')

@section('content')
@include('public.infographics._sub-hero', ['title' => 'Indeks Desa Membangun', 'subtitle' => 'Skor indeks dan indikator struktural dari data admin.', 'kicker' => 'IDM'])

<section class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-6 lg:py-14">
    @if (! $summary)
        <div class="frontend-empty">Ringkasan IDM belum diisi dari admin.</div>
    @else
        <div class="mb-10 grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
            <div class="frontend-card p-5">
                <p class="text-xs font-bold uppercase text-slate-500">Skor IDM</p>
                <p class="mt-2 text-3xl font-extrabold text-emerald-900">{{ number_format((float) $summary->idm_score, 4, ',', '.') }}</p>
                <p class="mt-1 text-xs text-slate-500">Tahun {{ $summary->year }}</p>
            </div>
            <div class="frontend-card p-5">
                <p class="text-xs font-bold uppercase text-slate-500">Status</p>
                <p class="mt-2 text-xl font-bold text-slate-950">{{ $summary->idm_status }}</p>
            </div>
            <div class="frontend-card p-5">
                <p class="text-xs font-bold uppercase text-slate-500">Target status</p>
                <p class="mt-2 text-xl font-bold text-slate-950">{{ $summary->target_status ?: '—' }}</p>
            </div>
            <div class="frontend-card p-5">
                <p class="text-xs font-bold uppercase text-slate-500">Skor tambahan dibutuhkan</p>
                <p class="mt-2 text-xl font-bold text-slate-950">{{ number_format((float) ($summary->additional_score_needed ?? 0), 4, ',', '.') }}</p>
            </div>
        </div>

        <div class="mb-10 grid gap-4 md:grid-cols-3">
            <div class="frontend-card p-5">
                <p class="text-sm font-semibold text-slate-500">IKS</p>
                <p class="mt-2 text-2xl font-extrabold">{{ number_format((float) $summary->iks_score, 4, ',', '.') }}</p>
            </div>
            <div class="frontend-card p-5">
                <p class="text-sm font-semibold text-slate-500">IKE</p>
                <p class="mt-2 text-2xl font-extrabold">{{ number_format((float) $summary->ike_score, 4, ',', '.') }}</p>
            </div>
            <div class="frontend-card p-5">
                <p class="text-sm font-semibold text-slate-500">IKL</p>
                <p class="mt-2 text-2xl font-extrabold">{{ number_format((float) $summary->ikl_score, 4, ',', '.') }}</p>
            </div>
        </div>

        @if ($history->count() > 1)
            <div class="frontend-card mb-10 p-6">
                <h2 class="text-lg font-bold text-slate-950">Tren skor tahunan</h2>
                <div class="mt-6 h-72">
                    <canvas id="chartIdmHist"></canvas>
                </div>
            </div>
            @push('scripts')
            <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
            <script>
                document.addEventListener('DOMContentLoaded', function () {
                    var ctx = document.getElementById('chartIdmHist');
                    if (!ctx || typeof Chart === 'undefined') return;
                    new Chart(ctx, {
                        type: 'line',
                        data: {
                            labels: @json($history->pluck('year')),
                            datasets: [{
                                label: 'IDM',
                                data: @json($history->pluck('idm_score')),
                                borderColor: 'rgb(5, 150, 105)',
                                tension: 0.25,
                                fill: false,
                            }]
                        },
                        options: { responsive: true, maintainAspectRatio: false, scales: { y: { beginAtZero: false } } }
                    });
                });
            </script>
            @endpush
        @endif

        @foreach ($indicators as $category => $items)
            <div class="frontend-card mb-8 overflow-hidden p-0">
                <div class="border-b border-slate-100 bg-slate-50 px-6 py-4">
                    <h2 class="text-lg font-bold text-slate-950">{{ $category }}</h2>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm">
                        <thead class="text-left text-xs font-bold uppercase text-slate-500">
                            <tr>
                                <th class="px-4 py-3">No</th>
                                <th class="px-4 py-3">Indikator</th>
                                <th class="px-4 py-3 text-right">Nilai</th>
                                <th class="px-4 py-3 text-right">Skor</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @foreach ($items as $ind)
                                <tr>
                                    <td class="px-4 py-3">{{ $ind->indicator_no }}</td>
                                    <td class="px-4 py-3">{{ $ind->indicator_name }}</td>
                                    <td class="px-4 py-3 text-right">{{ $ind->value !== null ? number_format((float) $ind->value, 4, ',', '.') : '—' }}</td>
                                    <td class="px-4 py-3 text-right font-semibold">{{ $ind->score !== null ? (int) $ind->score : '—' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endforeach
    @endif
</section>
@endsection
