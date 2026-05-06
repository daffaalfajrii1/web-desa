@extends('public.layouts.app')

@section('title', 'Stunting')

@section('content')
@include('public.infographics._sub-hero', ['title' => 'Monitoring Stunting & Gizi', 'subtitle' => 'Rekapitulasi data anak menurut entri admin.', 'kicker' => 'Kesehatan'])

<section class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-6 lg:py-14">
    @if ($years->isNotEmpty())
        <form method="GET" class="mb-8">
            <label for="tahun" class="text-sm font-semibold text-slate-700">Tahun</label>
            <select id="tahun" name="tahun" class="ml-3 mt-2 rounded-lg border-slate-200 px-3 py-2 text-sm" onchange="this.form.submit()">
                <option value="">Semua</option>
                @foreach ($years as $y)
                    <option value="{{ $y }}" @selected((string) $year === (string) $y)>{{ $y }}</option>
                @endforeach
            </select>
        </form>
    @endif

    @if (! $records)
        <div class="frontend-empty">Data stunting belum tersedia.</div>
    @else
        <div class="mb-8 grid gap-4 sm:grid-cols-3">
            @foreach (['normal' => 'Normal', 'stunting' => 'Stunting', 'berisiko' => 'Berisiko'] as $k => $label)
                <div class="frontend-card p-5">
                    <p class="text-sm font-semibold text-slate-500">{{ $label }}</p>
                    <p class="mt-2 text-3xl font-extrabold text-slate-950">{{ number_format((int) ($summary[$k] ?? 0), 0, ',', '.') }}</p>
                </div>
            @endforeach
        </div>

        <div class="frontend-card mb-10 p-6">
            <h2 class="text-lg font-bold text-slate-950">Komposisi status</h2>
            <div class="mx-auto mt-6 h-64 max-w-sm">
                <canvas id="chartStunting"></canvas>
            </div>
        </div>

        <div class="frontend-card mb-10 overflow-x-auto p-0">
            <table class="min-w-full text-sm">
                <thead class="bg-slate-50 text-left text-xs font-bold uppercase text-slate-600">
                    <tr>
                        <th class="px-4 py-3">Anak</th>
                        <th class="px-4 py-3">Dusun</th>
                        <th class="px-4 py-3">Status</th>
                        <th class="px-4 py-3">Gizi</th>
                        <th class="px-4 py-3 text-right">Usia (bln)</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach ($records as $r)
                        <tr>
                            <td class="px-4 py-3 font-semibold">{{ $r->child_name }}</td>
                            <td class="px-4 py-3">{{ $r->hamlet?->name ?: '—' }}</td>
                            <td class="px-4 py-3">{{ $r->stunting_status }}</td>
                            <td class="px-4 py-3">{{ $r->nutrition_status }}</td>
                            <td class="px-4 py-3 text-right">{{ $r->age_in_months ?? '—' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-8">{{ $records->links() }}</div>

        @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                var ctx = document.getElementById('chartStunting');
                if (!ctx || typeof Chart === 'undefined') return;
                new Chart(ctx, {
                    type: 'doughnut',
                    data: {
                        labels: ['Normal', 'Stunting', 'Berisiko'],
                        datasets: [{
                            data: [
                                {{ (int) ($summary['normal'] ?? 0) }},
                                {{ (int) ($summary['stunting'] ?? 0) }},
                                {{ (int) ($summary['berisiko'] ?? 0) }},
                            ],
                            backgroundColor: ['rgba(34,197,94,.85)', 'rgba(239,68,68,.85)', 'rgba(234,179,8,.85)'],
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
