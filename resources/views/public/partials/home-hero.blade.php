@php
    $hasCarousel = $banners->isNotEmpty();
    $heroBanner = $banners->first();
    $heroImage = $heroBanner ? $heroBanner->image_url : null;
    $displayName = $village?->village_name ?: 'Portal Informasi Desa';
    $intro = $carouselIntro ?? 'Portal informasi dan layanan digital desa - transparan, mudah diakses, dan mendukung partisipasi warga.';
    $loc = collect([$village?->district_name, $village?->regency_name, $village?->province_name])->filter()->implode(' - ');
    $todayLabel = now()->locale('id')->translatedFormat('l, d F Y');
    $clockLabel = now()->format('H:i:s').' WIB';
    $customTickerItems = collect(preg_split('/\r\n|\r|\n/', (string) ($village?->marquee_text ?? '')))
        ->map(fn ($item) => trim($item))
        ->filter()
        ->values();
    $defaultTickerItems = collect([
        'Website resmi '.$displayName,
        $todayLabel,
        'Jam layanan: '.$clockLabel,
        $loc !== '' ? $loc : null,
        $village?->address ? \Illuminate\Support\Str::limit(strip_tags((string) $village->address), 120) : null,
        $intro,
    ])->filter()->values();
    $tickerItems = $customTickerItems->isNotEmpty() ? $customTickerItems : $defaultTickerItems;
@endphp

<section class="relative overflow-hidden border-b border-emerald-800/50 bg-gradient-to-br from-emerald-950 via-emerald-900 to-teal-950 text-white" aria-label="Identitas portal desa">
    <div class="pointer-events-none absolute inset-0 bg-[radial-gradient(ellipse_80%_60%_at_100%_-20%,rgba(251,191,36,0.12),transparent_50%)]"></div>
    <div class="relative mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:flex lg:items-stretch lg:justify-between lg:gap-10 lg:px-6 lg:py-8">
        <div class="flex min-w-0 flex-1 gap-4 sm:gap-5">
            @if ($village?->logo_url)
                <span class="grid h-14 w-14 shrink-0 overflow-hidden rounded-2xl bg-white/10 p-1.5 shadow-lg shadow-emerald-950/40 ring-2 ring-amber-300/25 sm:h-16 sm:w-16">
                    <img src="{{ $village->logo_url }}" alt="" class="h-full w-full object-contain" width="64" height="64">
                </span>
            @else
                <span class="grid h-14 w-14 shrink-0 place-items-center rounded-2xl bg-emerald-800/80 ring-2 ring-white/10 sm:h-16 sm:w-16">
                    <svg class="h-8 w-8 text-amber-200/95" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true"><path d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                </span>
            @endif
            <div class="min-w-0">
                <p class="text-[11px] font-bold uppercase tracking-[0.2em] text-amber-200/95 sm:text-xs">
                    Website resmi <span class="text-emerald-100/95">{{ $displayName }}</span>
                </p>
                <h2 class="mt-1.5 text-2xl font-extrabold leading-tight tracking-tight text-white sm:text-3xl">
                    {{ $displayName }}
                </h2>
                @if ($loc !== '')
                    <p class="mt-2 text-sm font-medium text-emerald-100/85">{{ $loc }}</p>
                @elseif ($village?->address)
                    <p class="mt-2 line-clamp-2 text-sm font-medium text-emerald-100/85">{{ \Illuminate\Support\Str::limit(strip_tags((string) $village->address), 120) }}</p>
                @endif
            </div>
        </div>

        <div class="mt-6 flex min-w-0 flex-col justify-center border-t border-white/10 pt-6 lg:mt-0 lg:max-w-xl lg:border-l lg:border-t-0 lg:pl-10 lg:pt-0">
            <div class="public-marquee rounded-lg border border-white/10 bg-white/10 py-3 backdrop-blur" aria-label="Teks berjalan informasi desa">
                <div class="public-marquee-track">
                    @for ($loopIndex = 0; $loopIndex < 2; $loopIndex++)
                        @foreach ($tickerItems as $item)
                            <span class="public-marquee-item">{{ $item }}</span>
                        @endforeach
                    @endfor
                </div>
            </div>
            <div class="public-marquee mt-3 rounded-lg border border-amber-200/15 bg-amber-200/10 py-2 text-amber-100" aria-label="Teks berjalan waktu layanan">
                <div class="public-marquee-track public-marquee-track-slow">
                    @for ($loopIndex = 0; $loopIndex < 2; $loopIndex++)
                        <span class="public-marquee-item">Hari ini {{ $todayLabel }}</span>
                        <span class="public-marquee-item">Waktu lokal <span data-public-clock>{{ $clockLabel }}</span></span>
                        <span class="public-marquee-item">Cek layanan mandiri dengan nomor registrasi</span>
                        <span class="public-marquee-item">Cari informasi, infografis, wisata, dan lapak dari menu pencarian</span>
                    @endfor
                </div>
            </div>
        </div>
    </div>
</section>

@if ($hasCarousel)
    <section class="relative min-h-[520px] overflow-hidden bg-emerald-950 text-white lg:min-h-[560px]" id="public-hero-carousel" aria-label="Banner beranda">
        @foreach ($banners as $index => $banner)
            <div
                class="hero-slide absolute inset-0 transition-opacity duration-[800ms] ease-out {{ $index === 0 ? 'z-10 opacity-100' : 'z-0 opacity-0' }}"
                data-hero-slide="{{ $index }}"
                @if ($banner->subtitle) data-hero-subtitle="{{ $banner->subtitle }}" @endif
            >
                <img src="{{ $banner->image_url }}" alt="{{ $banner->title ?: 'Banner desa' }}" class="h-full w-full object-cover" loading="{{ $index === 0 ? 'eager' : 'lazy' }}">
                <div class="absolute inset-0 bg-gradient-to-r from-emerald-950 via-emerald-950/88 to-emerald-900/35"></div>
            </div>
        @endforeach

        <div class="relative z-20 mx-auto grid min-h-[520px] max-w-7xl items-center gap-8 px-4 py-12 sm:px-6 lg:min-h-[560px] lg:grid-cols-[1.05fr_0.95fr] lg:px-6 lg:py-20">
            <div class="max-w-2xl">
                <div class="inline-flex items-center gap-2 rounded-lg border border-white/15 bg-white/10 px-3 py-2 text-sm font-semibold text-emerald-50 backdrop-blur">
                    <span class="h-2 w-2 rounded-lg bg-amber-300"></span>
                    Website Resmi Desa
                </div>
                <h1 id="hero-carousel-title" class="mt-5 text-4xl font-extrabold leading-tight sm:text-5xl lg:text-6xl">
                    {{ $displayName }}
                </h1>
                <p id="hero-carousel-subtitle" class="mt-5 max-w-xl text-base leading-7 text-emerald-50 sm:text-lg">
                    {{ $heroBanner?->subtitle ?: ($village?->address ?: 'Akses informasi, layanan publik, dan potensi desa dengan tampilan yang cepat dan nyaman.') }}
                </p>
                <div class="mt-7 flex flex-col gap-3 sm:flex-row">
                    <a href="{{ route('public.services.index') }}" class="inline-flex items-center justify-center rounded-lg bg-amber-300 px-5 py-3 text-sm font-bold text-slate-950 transition hover:bg-amber-200">
                        Layanan Desa
                    </a>
                    <a href="{{ route('public.complaints.create') }}" class="inline-flex items-center justify-center rounded-lg border border-white/25 bg-white/10 px-5 py-3 text-sm font-bold text-white backdrop-blur transition hover:bg-white/15">
                        Pengaduan Warga
                    </a>
                </div>
            </div>

            <div class="hidden lg:block">
                <div class="ml-auto max-w-md rounded-lg border border-white/15 bg-white/12 p-6 backdrop-blur">
                    <p class="text-sm font-semibold text-amber-200">Identitas Desa</p>
                    <div class="mt-5 grid gap-4">
                        <div>
                            <p class="text-sm text-emerald-100">Kecamatan</p>
                            <p class="text-lg font-bold">{{ $village?->district_name ?: '-' }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-emerald-100">Kabupaten/Kota</p>
                            <p class="text-lg font-bold">{{ $village?->regency_name ?: '-' }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-emerald-100">Provinsi</p>
                            <p class="text-lg font-bold">{{ $village?->province_name ?: '-' }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @if ($banners->count() > 1)
            <script>
                document.addEventListener('DOMContentLoaded', function () {
                    var slides = document.querySelectorAll('#public-hero-carousel [data-hero-slide]');
                    if (!slides.length || slides.length < 2) return;
                    var subtitleEl = document.getElementById('hero-carousel-subtitle');
                    var i = 0;
                    window.setInterval(function () {
                        slides[i].classList.remove('opacity-100', 'z-10');
                        slides[i].classList.add('opacity-0', 'z-0');
                        i = (i + 1) % slides.length;
                        slides[i].classList.remove('opacity-0', 'z-0');
                        slides[i].classList.add('opacity-100', 'z-10');
                        var subtitle = slides[i].getAttribute('data-hero-subtitle');
                        if (subtitleEl && subtitle) subtitleEl.textContent = subtitle;
                    }, 3000);
                });
            </script>
        @endif
    </section>
@else
    <section class="relative overflow-hidden bg-emerald-950 text-white">
        @if ($heroImage)
            <img src="{{ $heroImage }}" alt="{{ $heroBanner->title ?: 'Banner desa' }}" class="absolute inset-0 h-full w-full object-cover opacity-55">
            <div class="absolute inset-0 bg-gradient-to-r from-emerald-950 via-emerald-950/85 to-emerald-900/40"></div>
        @else
            <div class="absolute inset-0 bg-[radial-gradient(circle_at_top_left,rgba(251,191,36,0.2),transparent_32%),linear-gradient(135deg,#064e3b,#0f766e_55%,#1f2937)]"></div>
        @endif

        <div class="relative mx-auto grid min-h-[520px] max-w-7xl items-center gap-8 px-4 py-12 sm:px-6 lg:grid-cols-[1.05fr_0.95fr] lg:px-6 lg:py-20">
            <div class="max-w-2xl">
                <div class="inline-flex items-center gap-2 rounded-lg border border-white/15 bg-white/10 px-3 py-2 text-sm font-semibold text-emerald-50 backdrop-blur">
                    <span class="h-2 w-2 rounded-lg bg-amber-300"></span>
                    Website Resmi Desa
                </div>
                <h1 class="mt-5 text-4xl font-extrabold leading-tight sm:text-5xl lg:text-6xl">
                    {{ $displayName }}
                </h1>
                <p class="mt-5 max-w-xl text-base leading-7 text-emerald-50 sm:text-lg">
                    {{ $heroBanner?->subtitle ?: ($village?->address ?: 'Akses informasi, layanan publik, dan potensi desa dengan tampilan yang cepat dan nyaman.') }}
                </p>
                <div class="mt-7 flex flex-col gap-3 sm:flex-row">
                    <a href="{{ route('public.services.index') }}" class="inline-flex items-center justify-center rounded-lg bg-amber-300 px-5 py-3 text-sm font-bold text-slate-950 transition hover:bg-amber-200">
                        Layanan Desa
                    </a>
                    <a href="{{ route('public.complaints.create') }}" class="inline-flex items-center justify-center rounded-lg border border-white/25 bg-white/10 px-5 py-3 text-sm font-bold text-white backdrop-blur transition hover:bg-white/15">
                        Pengaduan Warga
                    </a>
                </div>
            </div>

            <div class="hidden lg:block">
                <div class="ml-auto max-w-md rounded-lg border border-white/15 bg-white/12 p-6 backdrop-blur">
                    <p class="text-sm font-semibold text-amber-200">Identitas Desa</p>
                    <div class="mt-5 grid gap-4">
                        <div>
                            <p class="text-sm text-emerald-100">Kecamatan</p>
                            <p class="text-lg font-bold">{{ $village?->district_name ?: '-' }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-emerald-100">Kabupaten/Kota</p>
                            <p class="text-lg font-bold">{{ $village?->regency_name ?: '-' }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-emerald-100">Provinsi</p>
                            <p class="text-lg font-bold">{{ $village?->province_name ?: '-' }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endif

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var clocks = document.querySelectorAll('[data-public-clock]');
            if (!clocks.length) return;

            var formatter = new Intl.DateTimeFormat('id-ID', {
                hour: '2-digit',
                minute: '2-digit',
                second: '2-digit',
                hour12: false,
                timeZone: 'Asia/Jakarta'
            });

            function refreshClock() {
                var current = formatter.format(new Date()).replace(/\./g, ':') + ' WIB';
                clocks.forEach(function (clock) {
                    clock.textContent = current;
                });
            }

            refreshClock();
            window.setInterval(refreshClock, 1000);
        });
    </script>
@endpush
