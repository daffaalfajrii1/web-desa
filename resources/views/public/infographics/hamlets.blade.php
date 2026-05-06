@extends('public.layouts.app')

@section('title', 'Data Dusun')

@section('content')
@include('public.infographics._sub-hero', ['title' => 'Data Dusun', 'subtitle' => 'Wilayah administratif dan ringkasan kependudukan per dusun.', 'kicker' => 'Wilayah'])

<section class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-6 lg:py-14">
    @if ($year)
        <p class="mb-6 text-sm font-semibold text-slate-600">Data ringkas penduduk mengacu tahun <strong>{{ $year }}</strong> (sesuai entri admin).</p>
    @endif

    @if ($hamlets->isEmpty())
        <div class="frontend-empty">Data dusun atau ringkasan penduduk belum diisi dari admin.</div>
    @else
        <div class="frontend-card mb-10 overflow-x-auto p-0">
            <table class="min-w-full divide-y divide-slate-200 text-sm">
                <thead class="bg-slate-50 text-left text-xs font-bold uppercase tracking-wide text-slate-600">
                    <tr>
                        <th class="px-4 py-3">Dusun</th>
                        <th class="px-4 py-3 text-right">Laki-laki</th>
                        <th class="px-4 py-3 text-right">Perempuan</th>
                        <th class="px-4 py-3 text-right">Jiwa</th>
                        <th class="px-4 py-3 text-right">KK</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach ($hamlets as $row)
                        <tr class="hover:bg-emerald-50/40">
                            <td class="px-4 py-3 font-semibold text-slate-950">{{ $row['model']->name }}</td>
                            <td class="px-4 py-3 text-right">{{ $row['summary'] ? number_format((int) $row['summary']->male_count, 0, ',', '.') : '—' }}</td>
                            <td class="px-4 py-3 text-right">{{ $row['summary'] ? number_format((int) $row['summary']->female_count, 0, ',', '.') : '—' }}</td>
                            <td class="px-4 py-3 text-right font-bold text-emerald-800">{{ $row['total'] ? number_format($row['total'], 0, ',', '.') : '—' }}</td>
                            <td class="px-4 py-3 text-right">{{ $row['summary'] ? number_format((int) $row['summary']->total_kk, 0, ',', '.') : '—' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="frontend-card p-6">
            <h2 class="text-lg font-bold text-slate-950">Jiwa per dusun</h2>
            <div class="mt-6 h-72 max-w-4xl">
                <canvas id="chartHamlets"></canvas>
            </div>
        </div>

        @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                var labels = @json($hamlets->map(fn ($r) => $r['model']->name)->values());
                var data = @json($hamlets->map(fn ($r) => $r['total'])->values());
                var ctx = document.getElementById('chartHamlets');
                if (!ctx || typeof Chart === 'undefined') return;
                new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: labels,
                        datasets: [{
                            label: 'Jiwa',
                            data: data,
                            backgroundColor: 'rgba(5, 150, 105, 0.65)',
                            borderRadius: 8,
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: { legend: { display: false } },
                        scales: {
                            x: { ticks: { maxRotation: 45, minRotation: 0 } },
                            y: { beginAtZero: true }
                        }
                    }
                });
            });
        </script>
        @endpush
    @endif
</section>
@endsection
