@extends('public.layouts.app')

@section('title', 'Statistik Penduduk')

@section('content')
@include('public.infographics._sub-hero', ['title' => 'Statistik Penduduk', 'subtitle' => 'Distribusi indikator kependudukan per kategori sesuai data admin.', 'kicker' => 'Detail'])

@php
    $categoryIcons = [
        'agama' => 'M12 3l7 4v5c0 5-3.4 9.4-7 10-3.6-.6-7-5-7-10V7l7-4zm0 4.4V20',
        'pendidikan' => 'M3 8l9-5 9 5-9 5-9-5zm3 2.7V15l6 3 6-3v-4.3',
        'umur' => 'M12 7v5l3 2m6-2a9 9 0 11-18 0 9 9 0 0118 0z',
        'perkawinan' => 'M9.5 10.5a3.5 3.5 0 115 0L12 13l-2.5-2.5zM6 18h12',
        'wajib_pilih' => 'M5 7h14M5 12h14M5 17h9m2.5-1.5l1.5 1.5 3-3',
        'pekerjaan' => 'M4 9h16v9H4V9zm3-3h10v3H7V6z',
    ];
@endphp

<section class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-6 lg:py-14">
    @if ($years->isNotEmpty())
        <form method="GET" class="mb-10 flex flex-wrap gap-4">
            <div>
                <label for="tahun" class="block text-sm font-semibold text-slate-700">Tahun</label>
                <select id="tahun" name="tahun" class="mt-2 rounded-lg border-slate-200 px-4 py-2 text-sm shadow-sm" onchange="this.form.submit()">
                    @foreach ($years as $y)
                        <option value="{{ $y }}" @selected((string) $year === (string) $y)>{{ $y }}</option>
                    @endforeach
                </select>
            </div>
        </form>
    @endif

    @if ($byCategory->isEmpty())
        <div class="frontend-empty">Belum ada statistik penduduk untuk tahun yang dipilih.</div>
    @else
        <div class="mb-8 grid gap-3 sm:grid-cols-2 xl:grid-cols-3">
            @foreach ($byCategory as $cat)
                <article class="rounded-2xl border border-emerald-100 bg-white/95 p-4 shadow-sm ring-1 ring-emerald-50">
                    <div class="flex items-center gap-3">
                        <span class="inline-flex h-10 w-10 items-center justify-center rounded-xl bg-emerald-50 text-emerald-700">
                            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                                <path d="{{ $categoryIcons[$cat['key']] ?? 'M4 19h16M7 15V9m5 6V6m5 9v-3' }}" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </span>
                        <div>
                            <p class="text-xs font-bold uppercase tracking-[0.14em] text-slate-500">Kategori</p>
                            <p class="text-sm font-extrabold text-slate-900">{{ $cat['label'] }}</p>
                        </div>
                    </div>
                    <p class="mt-3 text-sm text-slate-600">{{ $cat['hamlets']->count() }} dusun · {{ number_format($cat['grand_total'], 0, ',', '.') }} total data</p>
                </article>
            @endforeach
        </div>

        @foreach ($byCategory as $cat)
            <div class="frontend-card mb-10 p-6">
                <div class="flex flex-wrap items-center justify-between gap-3 border-b border-emerald-100 pb-4">
                    <div class="flex items-center gap-3">
                        <span class="inline-flex h-10 w-10 items-center justify-center rounded-xl bg-emerald-50 text-emerald-700">
                            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                                <path d="{{ $categoryIcons[$cat['key']] ?? 'M4 19h16M7 15V9m5 6V6m5 9v-3' }}" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </span>
                        <h2 class="text-xl font-extrabold text-slate-950">{{ $cat['label'] }}</h2>
                    </div>
                    <p class="text-sm font-semibold text-slate-500">{{ number_format($cat['grand_total'], 0, ',', '.') }} total</p>
                </div>

                <div class="mt-4 overflow-x-auto">
                    <table class="min-w-full text-sm">
                        <thead class="border-b border-slate-200 text-left text-xs font-bold uppercase text-slate-500">
                            <tr>
                                <th class="py-2 pr-4">Item</th>
                                @foreach ($cat['hamlets'] as $hamletName)
                                    <th class="py-2 px-3 text-right">{{ $hamletName }}</th>
                                @endforeach
                                <th class="py-2 pl-3 text-right">Total</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @foreach ($cat['rows'] as $row)
                                <tr>
                                    <td class="py-2 pr-4">{{ $row['item'] }}</td>
                                    @foreach ($cat['hamlets'] as $hamletName)
                                        <td class="py-2 px-3 text-right">{{ number_format((int) ($row['values'][$hamletName] ?? 0), 0, ',', '.') }}</td>
                                    @endforeach
                                    <td class="py-2 pl-3 text-right font-bold">{{ number_format((int) $row['total'], 0, ',', '.') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="mt-8 h-80">
                    <canvas id="popCat{{ $loop->index }}"></canvas>
                </div>
            </div>
        @endforeach

        @php
            $palette = ['#059669', '#0ea5e9', '#f59e0b', '#8b5cf6', '#ef4444', '#14b8a6', '#f97316', '#64748b'];
            $chartConfigs = $byCategory->map(function ($cat) use ($palette) {
                $hamlets = collect($cat['hamlets'])->values();
                $datasets = $hamlets->map(function ($hamletName, $hamletIndex) use ($cat, $palette) {
                    return [
                        'label' => $hamletName,
                        'data' => collect($cat['rows'])->map(fn ($row) => (int) ($row['values'][$hamletName] ?? 0))->values(),
                        'backgroundColor' => $palette[$hamletIndex % count($palette)],
                        'borderRadius' => 6,
                        'maxBarThickness' => 40,
                    ];
                })->values();

                return [
                    'labels' => collect($cat['rows'])->pluck('item')->map(fn ($n) => \Illuminate\Support\Str::limit((string) $n, 30))->values(),
                    'datasets' => $datasets,
                ];
            })->values();
        @endphp

        @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                var charts = @json($chartConfigs);
                charts.forEach(function (cfg, idx) {
                    var el = document.getElementById('popCat' + idx);
                    if (!el || typeof Chart === 'undefined') return;
                    new Chart(el, {
                        type: 'bar',
                        data: {
                            labels: cfg.labels,
                            datasets: cfg.datasets
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: {
                                legend: { display: true, position: 'bottom' },
                            },
                            scales: {
                                x: { stacked: false, ticks: { maxRotation: 45, minRotation: 20 } },
                                y: { beginAtZero: true, ticks: { precision: 0 } }
                            }
                        }
                    });
                });
            });
        </script>
        @endpush
    @endif
</section>
@endsection
