@extends('public.layouts.app')

@section('title', 'Layanan')

@section('content')
<section class="bg-emerald-950 py-12 text-white">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-6">
        <p class="text-sm font-semibold text-amber-200">Desa siap melayani</p>
        <h1 class="mt-2 text-3xl font-extrabold sm:text-4xl">Layanan & kanal partisipasi</h1>
        <p class="mt-3 max-w-3xl text-sm leading-6 text-emerald-50">
            Layanan mandiri, pengaduan ber-tiket, dan pintu informasi publik dalam satu halaman.
        </p>
    </div>
</section>

<section class="mx-auto max-w-7xl space-y-12 px-4 py-12 sm:px-6 lg:px-6 lg:py-16">
    <form method="GET" action="{{ route('public.services.index') }}" class="rounded-lg border border-slate-200 bg-white p-4 shadow-sm">
        <div class="grid gap-3 md:grid-cols-[1fr_auto_auto]">
            <input
                type="search"
                name="q"
                value="{{ $search ?? '' }}"
                placeholder="Cari layanan, pengaduan, dokumen, atau halaman..."
                class="block w-full rounded-lg border-slate-200 px-4 py-3 text-sm shadow-sm focus:border-emerald-500 focus:ring-emerald-500"
            >
            <button type="submit" class="rounded-lg bg-emerald-700 px-5 py-3 text-sm font-bold text-white transition hover:bg-emerald-800">Cari</button>
            <a href="{{ route('public.services.index') }}" class="rounded-lg bg-slate-100 px-5 py-3 text-center text-sm font-bold text-slate-700 transition hover:bg-slate-200">Reset</a>
        </div>
    </form>

    <div class="grid gap-5 lg:grid-cols-3">
        <a href="{{ route('public.self-services.index') }}" class="frontend-card group p-6 transition hover:ring-emerald-200">
            <span class="inline-flex h-12 w-12 items-center justify-center rounded-xl bg-emerald-50 text-emerald-700 ring-1 ring-emerald-100">
                <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M9 12l2 2 4-5M5 4h14v16H5z"/></svg>
            </span>
            <h2 class="mt-4 text-lg font-bold text-slate-950">Ajukan layanan mandiri</h2>
            <p class="mt-2 text-sm leading-6 text-slate-600">Pilih formulir yang tersedia dan kirim berkas sesuai petunjuk (termasuk verifikasi captcha).</p>
            <span class="mt-4 inline-flex text-sm font-bold text-emerald-700 group-hover:underline">Buka layanan -></span>
        </a>
        <a href="{{ route('public.self-services.status') }}" class="frontend-card group p-6 transition hover:ring-emerald-200">
            <span class="inline-flex h-12 w-12 items-center justify-center rounded-xl bg-sky-50 text-sky-700 ring-1 ring-sky-100">
                <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M9 5H7a2 2 0 00-2 2v12h14V7a2 2 0 00-2-2h-2M9 5a3 3 0 006 0M9 14l2 2 4-4"/></svg>
            </span>
            <h2 class="mt-4 text-lg font-bold text-slate-950">Cek progres layanan</h2>
            <p class="mt-2 text-sm leading-6 text-slate-600">Masukkan nomor registrasi untuk melihat status, catatan, dan unduh hasil jika sudah tersedia.</p>
            <span class="mt-4 inline-flex text-sm font-bold text-emerald-700 group-hover:underline">Cek registrasi -></span>
        </a>
        <a href="{{ route('public.complaints.create') }}" class="frontend-card group p-6 transition hover:ring-emerald-200">
            <span class="inline-flex h-12 w-12 items-center justify-center rounded-xl bg-rose-50 text-rose-700 ring-1 ring-rose-100">
                <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M21 15a4 4 0 01-4 4H8l-6 4V7a4 4 0 014-4h10a4 4 0 014 4zM13 8H7M17 12h-6"/></svg>
            </span>
            <h2 class="mt-4 text-lg font-bold text-slate-950">Pengaduan warga</h2>
            <p class="mt-2 text-sm leading-6 text-slate-600">Laporkan kendala dan cek status pengaduan dengan nomor tiket.</p>
            <span class="mt-4 inline-flex text-sm font-bold text-emerald-700 group-hover:underline">Mulai mengadu -></span>
        </a>
    </div>

    <div>
        <div class="mb-6 flex flex-wrap items-end justify-between gap-4">
            <div>
                <p class="text-sm font-semibold text-emerald-700">Tipe: administrasi desa</p>
                <h2 class="mt-1 text-2xl font-bold text-slate-950">Layanan mandiri</h2>
                <p class="mt-2 max-w-2xl text-sm text-slate-600">Form dinamis per layanan dengan captcha ringan untuk perlindungan spam.</p>
            </div>
            <span class="rounded-full bg-emerald-50 px-4 py-1.5 text-xs font-bold text-emerald-900">{{ $mandiriCount }} layanan tampil</span>
        </div>

        @forelse ($grouped['mandiri'] ?? [] as $service)
            <div class="frontend-card mb-5 p-6">
                <div class="flex flex-col gap-4 md:flex-row md:items-start md:justify-between">
                    <div>
                        <p class="text-xs font-bold uppercase tracking-wide text-emerald-700">{{ $service->service_code }}</p>
                        <h3 class="mt-1 text-xl font-bold text-slate-950">{{ $service->service_name }}</h3>
                        @if ($service->description)
                            <p class="mt-2 max-w-2xl text-sm leading-6 text-slate-600">{{ \Illuminate\Support\Str::limit(strip_tags((string) $service->description), 220) }}</p>
                        @endif
                        <p class="mt-3 text-xs font-semibold text-slate-500">{{ $service->fields_count }} field formulir</p>
                    </div>
                    <a href="{{ route('public.self-services.show', $service) }}" class="inline-flex shrink-0 items-center justify-center rounded-lg bg-emerald-700 px-5 py-3 text-sm font-bold text-white hover:bg-emerald-800">
                        Ajukan / isi form
                    </a>
                </div>
            </div>
        @empty
            <div class="frontend-empty">Belum ada layanan mandiri yang cocok dengan pencarian.</div>
        @endforelse
    </div>

    <div>
        <p class="text-sm font-semibold text-emerald-700">Tipe: transparansi</p>
        <h2 class="mt-1 text-2xl font-bold text-slate-950">Informasi & dokumen</h2>
        <div class="mt-6 grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
            <a href="{{ route('public.public-informations.index') }}" class="frontend-card p-5 hover:ring-emerald-200">
                <h3 class="font-bold text-slate-950">Informasi publik</h3>
                <p class="mt-2 text-sm text-slate-600">Daftar informasi yang wajib / dapat dibuka.</p>
            </a>
            <a href="{{ route('public.legal-products.index') }}" class="frontend-card p-5 hover:ring-emerald-200">
                <h3 class="font-bold text-slate-950">Produk hukum</h3>
                <p class="mt-2 text-sm text-slate-600">Peraturan dan produk hukum desa.</p>
            </a>
            <a href="{{ route('public.ppid.index') }}" class="frontend-card p-5 hover:ring-emerald-200">
                <h3 class="font-bold text-slate-950">PPID</h3>
                <p class="mt-2 text-sm text-slate-600">Pelayanan informasi dan dokumentasi desa.</p>
            </a>
        </div>
    </div>
</section>
@endsection
