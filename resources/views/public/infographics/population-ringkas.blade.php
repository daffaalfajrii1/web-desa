@extends('public.layouts.app')

@section('title', 'Ringkasan Penduduk')

@section('content')
@include('public.infographics._sub-hero', ['title' => 'Ringkasan Penduduk', 'subtitle' => 'Agregat jiwa dan KK berdasarkan data ringkasan admin.', 'kicker' => 'Kependudukan'])

<section class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-6 lg:py-14">
    @if ($years->isNotEmpty())
        <form method="GET" class="mb-8 flex flex-wrap items-end gap-4">
            <div>
                <label for="tahun" class="block text-sm font-semibold text-slate-700">Tahun</label>
                <select id="tahun" name="tahun" class="mt-2 rounded-lg border-slate-200 px-4 py-2 text-sm shadow-sm focus:border-emerald-500 focus:ring-emerald-500" onchange="this.form.submit()">
                    @foreach ($years as $y)
                        <option value="{{ $y }}" @selected((string) $year === (string) $y)>{{ $y }}</option>
                    @endforeach
                </select>
            </div>
        </form>
    @elseif ($year)
        <p class="mb-6 text-sm text-slate-600">Tahun data: <strong>{{ $year }}</strong></p>
    @endif

    <div class="mb-10 grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
        <div class="frontend-card p-5">
            <p class="text-sm font-semibold text-slate-500">Total jiwa</p>
            <p class="mt-2 text-3xl font-extrabold text-slate-950">{{ number_format($totals['total'], 0, ',', '.') }}</p>
        </div>
        <div class="frontend-card p-5">
            <p class="text-sm font-semibold text-slate-500">Laki-laki</p>
            <p class="mt-2 text-3xl font-extrabold text-sky-800">{{ number_format($totals['male'], 0, ',', '.') }}</p>
        </div>
        <div class="frontend-card p-5">
            <p class="text-sm font-semibold text-slate-500">Perempuan</p>
            <p class="mt-2 text-3xl font-extrabold text-rose-800">{{ number_format($totals['female'], 0, ',', '.') }}</p>
        </div>
        <div class="frontend-card p-5">
            <p class="text-sm font-semibold text-slate-500">Kepala keluarga</p>
            <p class="mt-2 text-3xl font-extrabold text-emerald-900">{{ number_format($totals['kk'], 0, ',', '.') }}</p>
        </div>
    </div>

    @if ($rows->isEmpty())
        <div class="frontend-empty">Belum ada baris ringkasan penduduk untuk tahun ini.</div>
    @else
        <div class="frontend-card mb-10 overflow-x-auto p-0">
            <table class="min-w-full divide-y divide-slate-200 text-sm">
                <thead class="bg-slate-50 text-left text-xs font-bold uppercase tracking-wide text-slate-600">
                    <tr>
                        <th class="px-4 py-3">Dusun</th>
                        <th class="px-4 py-3 text-right">L</th>
                        <th class="px-4 py-3 text-right">P</th>
                        <th class="px-4 py-3 text-right">Jiwa</th>
                        <th class="px-4 py-3 text-right">KK</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach ($rows as $r)
                        <tr>
                            <td class="px-4 py-3 font-semibold">{{ $r->hamlet?->name ?: '—' }}</td>
                            <td class="px-4 py-3 text-right">{{ number_format((int) $r->male_count, 0, ',', '.') }}</td>
                            <td class="px-4 py-3 text-right">{{ number_format((int) $r->female_count, 0, ',', '.') }}</td>
                            <td class="px-4 py-3 text-right font-bold">{{ number_format((int) $r->male_count + (int) $r->female_count, 0, ',', '.') }}</td>
                            <td class="px-4 py-3 text-right">{{ number_format((int) $r->total_kk, 0, ',', '.') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="frontend-card p-6">
            <h2 class="text-lg font-bold text-slate-950">Komposisi gender</h2>
            <div class="mx-auto mt-6 h-64 max-w-md">
                <canvas id="chartGender"></canvas>
            </div>
        </div>

        @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                var ctx = document.getElementById('chartGender');
                if (!ctx) return;
                new Chart(ctx, {
                    type: 'doughnut',
                    data: {
                        labels: ['Laki-laki', 'Perempuan'],
                        datasets: [{
                            data: [{{ $totals['male'] }}, {{ $totals['female'] }}],
                            backgroundColor: ['rgba(14, 165, 233, 0.85)', 'rgba(244, 63, 94, 0.85)'],
                            borderWidth: 0,
                        }]
                    },
                    options: { plugins: { legend: { position: 'bottom' } } }
                });
            });
        </script>
        @endpush
    @endif
</section>
@endsection
