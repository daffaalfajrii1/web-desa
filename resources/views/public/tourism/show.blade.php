@extends('public.layouts.app')

@section('title', $tourism->title)

@section('content')
<article>
    <header class="relative bg-emerald-950 py-12 text-white lg:py-16">
        @if ($mainImage)
            <img src="{{ $mainImage }}" alt="" class="absolute inset-0 h-full w-full object-cover opacity-45">
            <div class="absolute inset-0 bg-gradient-to-t from-emerald-950 via-emerald-950/90 to-emerald-900/75"></div>
        @endif
        <div class="relative mx-auto max-w-7xl px-4 sm:px-6 lg:px-6">
            <a href="{{ route('public.tourism.index') }}" class="inline-flex text-sm font-semibold text-amber-200 hover:text-amber-100">← Kembali ke wisata</a>
            <h1 class="mt-5 max-w-4xl text-3xl font-extrabold leading-tight sm:text-5xl">{{ $tourism->title }}</h1>
            @if ($tourism->excerpt)
                <p class="mt-4 max-w-2xl text-sm leading-6 text-emerald-100 sm:text-base">{{ $tourism->excerpt }}</p>
            @endif
            <div class="mt-6 flex flex-wrap gap-2">
                @if ($tourism->address)
                    <span class="rounded-lg bg-white/15 px-3 py-1.5 text-xs font-semibold">{{ $tourism->address }}</span>
                @endif
                @if ($tourism->contact_phone)
                    <span class="rounded-lg bg-white/15 px-3 py-1.5 text-xs font-semibold">Telp: {{ $tourism->contact_phone }}</span>
                @endif
                @if ($tourism->contact_person)
                    <span class="rounded-lg bg-white/15 px-3 py-1.5 text-xs font-semibold">{{ $tourism->contact_person }}</span>
                @endif
            </div>
        </div>
    </header>

    <div class="mx-auto grid max-w-7xl gap-10 px-4 py-10 sm:px-6 lg:grid-cols-[1.15fr_0.85fr] lg:px-6 lg:py-14">
        <div>
            @if ($mainImage)
                <img src="{{ $mainImage }}" alt="{{ $tourism->title }}" class="mb-8 max-h-[420px] w-full rounded-xl object-cover shadow-sm ring-1 ring-slate-200">
            @endif

            <div class="prose prose-slate max-w-none">
                {!! $bodyHtml ?: '<p>Deskripsi wisata akan ditampilkan di sini.</p>' !!}
            </div>

            @if ($gallery->isNotEmpty())
                <div class="mt-10">
                    <h2 class="text-lg font-bold text-slate-950">Galeri</h2>
                    <div class="mt-4 grid grid-cols-2 gap-3 sm:grid-cols-3">
                        @foreach ($gallery as $gurl)
                            <button type="button" class="block overflow-hidden rounded-lg ring-1 ring-slate-200" onclick="document.getElementById('tourism-lightbox-img').src=this.querySelector('img').src; document.getElementById('tourism-lightbox').classList.remove('hidden');">
                                <img src="{{ $gurl }}" alt="" class="aspect-square w-full object-cover transition hover:scale-105">
                            </button>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>

        <aside class="space-y-6">
            <div class="frontend-card p-6">
                <h2 class="text-lg font-bold text-slate-950">Informasi kunjungan</h2>
                <dl class="mt-4 space-y-3 text-sm">
                    @if ($tourism->open_time || $tourism->close_time)
                        <div>
                            <dt class="font-semibold text-slate-500">Jam operasional</dt>
                            <dd class="mt-1 text-slate-900">{{ $tourism->open_time ? substr((string) $tourism->open_time, 0, 5) : '—' }} – {{ $tourism->close_time ? substr((string) $tourism->close_time, 0, 5) : '—' }}</dd>
                        </div>
                    @endif
                    @if ($openDays->isNotEmpty())
                        <div>
                            <dt class="font-semibold text-slate-500">Hari buka</dt>
                            <dd class="mt-1 text-slate-900">{{ $openDays->implode(', ') }}</dd>
                        </div>
                    @endif
                    @if ($closedDays->isNotEmpty())
                        <div>
                            <dt class="font-semibold text-slate-500">Hari libur / tutup</dt>
                            <dd class="mt-1 text-slate-900">{{ $closedDays->implode(', ') }}</dd>
                        </div>
                    @endif
                </dl>
            </div>

            @if ($facilities->isNotEmpty())
                <div class="frontend-card p-6">
                    <h2 class="text-lg font-bold text-slate-950">Fasilitas</h2>
                    <ul class="mt-4 grid gap-2 text-sm text-slate-700">
                        @foreach ($facilities as $f)
                            <li class="flex gap-2 rounded-lg bg-emerald-50/80 px-3 py-2">
                                <span class="mt-1 h-1.5 w-1.5 shrink-0 rounded-full bg-emerald-600"></span>
                                <span>{{ $f }}</span>
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if ($mapEmbed)
                <div class="frontend-card overflow-hidden p-0">
                    <h2 class="border-b border-slate-100 px-6 py-4 text-lg font-bold text-slate-950">Peta lokasi</h2>
                    <div class="public-map-embed min-h-[280px] bg-slate-100">{!! $mapEmbed !!}</div>
                </div>
            @endif
        </aside>
    </div>

    <div id="tourism-lightbox" class="fixed inset-0 z-[100] hidden bg-black/80 p-4" role="dialog" onclick="this.classList.add('hidden')">
        <div class="flex h-full items-center justify-center">
            <img id="tourism-lightbox-img" src="" alt="" class="max-h-full max-w-full rounded-lg object-contain">
        </div>
    </div>
</article>
@endsection
