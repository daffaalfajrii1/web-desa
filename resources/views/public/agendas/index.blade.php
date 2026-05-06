@extends('public.layouts.app')

@section('title', 'Agenda Desa')

@section('content')
<section class="bg-emerald-950 py-12 text-white">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-6">
        <p class="text-sm font-semibold text-amber-200">Kegiatan desa</p>
        <h1 class="mt-2 text-3xl font-extrabold sm:text-4xl">Agenda Desa</h1>
        <p class="mt-3 max-w-2xl text-sm leading-6 text-emerald-50">Jadwal kegiatan desa disajikan dalam format timeline agar mudah dipantau warga.</p>
    </div>
</section>

<section class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-6 lg:py-14">
    <form method="GET" action="{{ route('public.agendas.index') }}" class="mb-8 rounded-lg border border-slate-200 bg-white p-4 shadow-sm">
        <div class="grid gap-3 sm:grid-cols-[1fr_auto_auto]">
            <input
                type="search"
                name="q"
                value="{{ $search }}"
                placeholder="Cari agenda, lokasi, atau penyelenggara..."
                class="block w-full rounded-lg border-slate-200 px-4 py-2.5 text-sm shadow-sm focus:border-emerald-500 focus:ring-emerald-500"
            >
            <button type="submit" class="rounded-lg bg-emerald-700 px-4 py-2.5 text-sm font-bold text-white transition hover:bg-emerald-800">Cari</button>
            <a href="{{ route('public.agendas.index') }}" class="rounded-lg bg-slate-100 px-4 py-2.5 text-sm font-bold text-slate-700 transition hover:bg-slate-200">Reset</a>
        </div>
    </form>

    @if ($items && $items->count())
        <div class="relative space-y-5 before:absolute before:bottom-2 before:left-[21px] before:top-2 before:w-0.5 before:bg-gradient-to-b before:from-emerald-200 before:via-emerald-300/70 before:to-transparent sm:before:left-[27px]">
            @foreach ($items as $agenda)
                @php
                    $thumb = $imageUrl($agenda->featured_image ?? null);
                    $dateLabel = $agenda->start_date?->translatedFormat('d M Y') ?: '-';
                    $timeLabel = $agenda->start_time
                        ? ($agenda->start_time.($agenda->end_time ? ' - '.$agenda->end_time : ''))
                        : 'Waktu menyusul';
                @endphp
                <article class="relative rounded-2xl border border-slate-200 bg-white p-4 shadow-sm ring-1 ring-slate-100 sm:p-5">
                    <span class="absolute left-[13px] top-8 h-4 w-4 rounded-full border-2 border-white bg-emerald-600 shadow sm:left-[19px]" aria-hidden="true"></span>
                    <div class="pl-8 sm:pl-10">
                        <div class="mb-3 flex flex-wrap items-center gap-2">
                            <span class="rounded-full bg-emerald-50 px-2.5 py-1 text-[11px] font-extrabold uppercase tracking-wide text-emerald-700">{{ $dateLabel }}</span>
                            <span class="rounded-full bg-slate-100 px-2.5 py-1 text-[11px] font-semibold text-slate-600">{{ $timeLabel }}</span>
                            @if ($agenda->location)
                                <span class="rounded-full bg-slate-100 px-2.5 py-1 text-[11px] font-semibold text-slate-600">{{ $agenda->location }}</span>
                            @endif
                        </div>

                        <div class="grid gap-4 sm:grid-cols-[minmax(0,1fr)_180px] sm:items-start">
                            <div class="min-w-0">
                                <h2 class="text-lg font-extrabold leading-snug text-slate-950">
                                    <a href="{{ route('public.agendas.show', $agenda->slug) }}" class="hover:text-emerald-800">{{ $agenda->title }}</a>
                                </h2>
                                <p class="mt-2 line-clamp-3 text-sm leading-6 text-slate-600">
                                    {{ \Illuminate\Support\Str::limit(strip_tags((string) $agenda->description), 220) ?: 'Detail agenda akan diinformasikan lebih lanjut.' }}
                                </p>
                                <div class="mt-3 flex flex-wrap items-center gap-3 text-xs font-semibold text-slate-500">
                                    <span>Penyelenggara: {{ $agenda->organizer ?: 'Pemerintah Desa' }}</span>
                                </div>
                            </div>
                            <a href="{{ route('public.agendas.show', $agenda->slug) }}" class="group block overflow-hidden rounded-xl border border-slate-200 bg-slate-100">
                                @if ($thumb)
                                    <img src="{{ $thumb }}" alt="{{ $agenda->title }}" class="h-28 w-full object-cover transition duration-300 group-hover:scale-[1.03]">
                                @else
                                    <div class="grid h-28 place-items-center text-xs font-extrabold uppercase tracking-wide text-emerald-700">Agenda</div>
                                @endif
                            </a>
                        </div>
                    </div>
                </article>
            @endforeach
        </div>

        <div class="mt-8">
            {{ $items->links() }}
        </div>
    @else
        <div class="frontend-empty">Agenda belum tersedia atau tidak cocok dengan pencarian.</div>
    @endif
</section>
@endsection
