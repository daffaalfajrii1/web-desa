@extends('public.layouts.app')

@section('title', 'APBDes')

@section('content')
@include('public.infographics._sub-hero', ['title' => 'Anggaran Pendapatan dan Belanja Desa', 'subtitle' => 'Ikhtisar keuangan desa dari modul APBDes.', 'kicker' => 'Keuangan'])

<section class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-6 lg:py-14">
    @if ($rows->isEmpty())
        <div class="frontend-empty">Data APBDes belum diisi dari admin.</div>
    @else
        @php
            $fmt = fn ($n) => 'Rp '.number_format((float) $n, 0, ',', '.');
        @endphp

        @if ($active)
            <div class="mb-10 grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
                <div class="frontend-card p-5">
                    <p class="text-xs font-bold uppercase text-slate-500">Pendapatan</p>
                    <p class="mt-2 text-xl font-extrabold text-emerald-900">{{ $fmt($active->pendapatan) }}</p>
                    <p class="mt-1 text-xs text-slate-500">Tahun {{ $active->year }}</p>
                </div>
                <div class="frontend-card p-5">
                    <p class="text-xs font-bold uppercase text-slate-500">Belanja</p>
                    <p class="mt-2 text-xl font-extrabold text-rose-900">{{ $fmt($active->belanja) }}</p>
                </div>
                <div class="frontend-card p-5">
                    <p class="text-xs font-bold uppercase text-slate-500">Surplus / defisit</p>
                    <p class="mt-2 text-xl font-extrabold text-slate-950">{{ $fmt($active->surplus_defisit) }}</p>
                </div>
                <div class="frontend-card p-5">
                    <p class="text-xs font-bold uppercase text-slate-500">SILPA (indikatif)</p>
                    <p class="mt-2 text-xl font-extrabold text-slate-950">{{ $fmt($active->silpa) }}</p>
                    <p class="mt-1 text-xs text-slate-500">Pembiayaan netto + surplus belanja</p>
                </div>
            </div>
        @endif

        <div class="frontend-card mb-10 overflow-x-auto p-0">
            <table class="min-w-full text-sm">
                <thead class="bg-slate-50 text-left text-xs font-bold uppercase text-slate-600">
                    <tr>
                        <th class="px-4 py-3">Tahun</th>
                        <th class="px-4 py-3 text-right">Pendapatan</th>
                        <th class="px-4 py-3 text-right">Belanja</th>
                        <th class="px-4 py-3 text-right">Pembiayaan terima</th>
                        <th class="px-4 py-3 text-right">Pembiayaan keluar</th>
                        <th class="px-4 py-3 text-right">Surplus</th>
                        <th class="px-4 py-3 text-right">SILPA</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach ($rows as $r)
                        <tr class="{{ $r->is_active ? 'bg-emerald-50/50' : '' }}">
                            <td class="px-4 py-3 font-semibold">{{ $r->year }} @if ($r->is_active)<span class="ml-2 rounded bg-emerald-600 px-2 py-0.5 text-[10px] font-bold text-white">AKTIF</span>@endif</td>
                            <td class="px-4 py-3 text-right">{{ $fmt($r->pendapatan) }}</td>
                            <td class="px-4 py-3 text-right">{{ $fmt($r->belanja) }}</td>
                            <td class="px-4 py-3 text-right">{{ $fmt($r->pembiayaan_penerimaan) }}</td>
                            <td class="px-4 py-3 text-right">{{ $fmt($r->pembiayaan_pengeluaran) }}</td>
                            <td class="px-4 py-3 text-right">{{ $fmt($r->surplus_defisit) }}</td>
                            <td class="px-4 py-3 text-right font-bold">{{ $fmt($r->silpa) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        @php
            $sortedRows = $rows->sortBy('year')->values();
            $chartLabels = $sortedRows->pluck('year')->values();
            $chartPendapatan = $sortedRows->pluck('pendapatan')->values();
            $chartBelanja = $sortedRows->pluck('belanja')->values();
            $chartSurplus = $sortedRows->pluck('surplus_defisit')->values();
        @endphp
        <div class="frontend-card overflow-hidden p-0 shadow-lg ring-1 ring-slate-200/90">
            <div class="border-b border-slate-100 bg-gradient-to-r from-slate-50 to-white px-6 py-5 sm:px-8">
                <h2 class="text-lg font-extrabold text-slate-950">Tren keuangan desa</h2>
                <p class="mt-1 max-w-3xl text-sm text-slate-500">Batang: pendapatan & belanja tiap tahun · Garis: surplus / defisit. Arahkan kursor untuk nominal lengkap.</p>
            </div>
            <div class="h-80 bg-white px-4 py-4 sm:h-96 sm:px-6 sm:pb-6">
                <canvas id="chartApbdes"></canvas>
            </div>
        </div>

        @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                var ctx = document.getElementById('chartApbdes');
                if (!ctx || typeof Chart === 'undefined') return;
                var fmtId = function (v) {
                    var n = Number(v);
                    if (!isFinite(n)) return v;
                    if (Math.abs(n) >= 1e12) return (n / 1e12).toLocaleString('id-ID', { maximumFractionDigits: 1 }) + ' T';
                    if (Math.abs(n) >= 1e9) return (n / 1e9).toLocaleString('id-ID', { maximumFractionDigits: 1 }) + ' M';
                    if (Math.abs(n) >= 1e6) return (n / 1e6).toLocaleString('id-ID', { maximumFractionDigits: 1 }) + ' jt';
                    return n.toLocaleString('id-ID');
                };
                new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: @json($chartLabels),
                        datasets: [
                            {
                                type: 'bar',
                                label: 'Pendapatan',
                                data: @json($chartPendapatan),
                                backgroundColor: 'rgba(5, 150, 105, 0.72)',
                                borderColor: 'rgb(4, 120, 87)',
                                borderWidth: 1,
                                borderRadius: 8,
                                order: 2,
                            },
                            {
                                type: 'bar',
                                label: 'Belanja',
                                data: @json($chartBelanja),
                                backgroundColor: 'rgba(225, 29, 72, 0.62)',
                                borderColor: 'rgb(190, 18, 60)',
                                borderWidth: 1,
                                borderRadius: 8,
                                order: 2,
                            },
                            {
                                type: 'line',
                                label: 'Surplus / defisit',
                                data: @json($chartSurplus),
                                borderColor: 'rgb(30, 64, 175)',
                                backgroundColor: 'rgba(30, 64, 175, 0.08)',
                                borderWidth: 2.5,
                                pointRadius: 5,
                                pointHoverRadius: 7,
                                pointBackgroundColor: '#fff',
                                pointBorderColor: 'rgb(30, 64, 175)',
                                pointBorderWidth: 2,
                                tension: 0.35,
                                fill: false,
                                yAxisID: 'y',
                                order: 1,
                            },
                        ],
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        interaction: { mode: 'index', intersect: false },
                        animation: {
                            duration: 900,
                            easing: 'easeOutQuart',
                        },
                        plugins: {
                            legend: {
                                position: 'top',
                                labels: {
                                    usePointStyle: true,
                                    padding: 16,
                                    font: { size: 12, weight: '600', family: 'Figtree, system-ui, sans-serif' },
                                },
                            },
                            tooltip: {
                                backgroundColor: 'rgba(15, 23, 42, 0.93)',
                                titleFont: { size: 13, weight: '700' },
                                bodyFont: { size: 12 },
                                padding: 12,
                                cornerRadius: 10,
                                callbacks: {
                                    label: function (c) {
                                        var v = c.parsed.y !== undefined ? c.parsed.y : c.parsed;
                                        return c.dataset.label + ': Rp ' + Number(v).toLocaleString('id-ID');
                                    },
                                },
                            },
                        },
                        scales: {
                            x: {
                                grid: { display: false },
                                ticks: { font: { weight: '600' } },
                            },
                            y: {
                                beginAtZero: true,
                                border: { display: false },
                                grid: { color: 'rgba(148, 163, 184, 0.22)' },
                                ticks: {
                                    font: { size: 11 },
                                    callback: fmtId,
                                },
                            },
                        },
                    },
                });
            });
        </script>
        @endpush
    @endif
</section>
@endsection
