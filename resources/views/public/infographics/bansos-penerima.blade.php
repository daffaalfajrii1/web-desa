@extends('public.layouts.app')

@section('title', 'Penerima Bansos')

@section('content')
@include('public.infographics._sub-hero', ['title' => 'Penerima Bansos', 'subtitle' => 'Daftar penerima sesuai entri admin.', 'kicker' => 'Bansos'])

<section class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-6 lg:py-14">
    <form method="GET" class="mb-8 flex flex-wrap items-end gap-4">
        <div>
            <label for="program" class="block text-sm font-semibold text-slate-700">Filter program</label>
            <select id="program" name="program" class="mt-2 rounded-lg border-slate-200 px-4 py-2 text-sm shadow-sm" onchange="this.form.submit()">
                <option value="">Semua program</option>
                @foreach ($programs as $p)
                    <option value="{{ $p->id }}" @selected((string) $selectedProgram === (string) $p->id)>{{ $p->name }}</option>
                @endforeach
            </select>
        </div>
    </form>

    @if ($recipients->isEmpty())
        <div class="frontend-empty">Belum ada data penerima.</div>
    @else
        <div class="frontend-card overflow-x-auto p-0">
            <table class="min-w-full text-sm">
                <thead class="bg-slate-50 text-left text-xs font-bold uppercase text-slate-600">
                    <tr>
                        <th class="px-4 py-3">Nama</th>
                        <th class="px-4 py-3">Program</th>
                        <th class="px-4 py-3">Dusun</th>
                        <th class="px-4 py-3">Status salur</th>
                        <th class="px-4 py-3 text-right">Nilai</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach ($recipients as $r)
                        <tr>
                            <td class="px-4 py-3 font-semibold">{{ $r->name }}</td>
                            <td class="px-4 py-3">{{ $r->program?->name ?: '—' }}</td>
                            <td class="px-4 py-3">{{ $r->hamlet?->name ?: '—' }}</td>
                            <td class="px-4 py-3">{{ $r->distribution_status ?: '—' }}</td>
                            <td class="px-4 py-3 text-right">{{ $r->amount !== null ? 'Rp '.number_format((float) $r->amount, 0, ',', '.') : '—' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="mt-8">{{ $recipients->links() }}</div>
    @endif
</section>
@endsection
