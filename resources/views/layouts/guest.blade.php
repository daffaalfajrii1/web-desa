@php
    $guestVillageSetting = null;

    try {
        if (\Illuminate\Support\Facades\Schema::hasTable('village_settings')) {
            $guestVillageSetting = \App\Models\VillageSetting::query()->first();
        }
    } catch (\Throwable $exception) {
        $guestVillageSetting = null;
    }

    $guestVillageName = $guestVillageSetting?->village_name ?: config('app.name', 'Web Desa');
    $guestLocation = collect([
        $guestVillageSetting?->district_name,
        $guestVillageSetting?->regency_name,
        $guestVillageSetting?->province_name,
    ])->filter()->implode(', ');
@endphp

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ $guestVillageName }}</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-slate-900 antialiased">
        <div class="min-h-screen bg-[#f6f8f5]">
            <div class="h-1 w-full bg-gradient-to-r from-emerald-600 via-teal-500 to-amber-400"></div>

            <div class="grid min-h-[calc(100vh-4px)] lg:grid-cols-[minmax(0,0.95fr)_minmax(420px,0.75fr)]">
                <aside class="relative hidden overflow-hidden bg-slate-950 text-white lg:flex">
                    <div class="absolute inset-y-0 right-0 w-px bg-white/10"></div>
                    <div class="relative flex min-h-full w-full flex-col justify-between px-12 py-10">
                        <a href="/" class="inline-flex items-center gap-3">
                            <span class="grid h-12 w-12 place-items-center rounded-lg bg-white text-emerald-700 shadow-lg shadow-emerald-950/20">
                                <x-application-logo class="h-8 w-8" />
                            </span>
                            <span>
                                <span class="block text-sm font-semibold uppercase text-emerald-200">Portal Desa</span>
                                <span class="mt-1 block text-lg font-semibold leading-tight text-white">{{ $guestVillageName }}</span>
                            </span>
                        </a>

                        <div class="max-w-xl">
                            <p class="text-sm font-semibold uppercase text-amber-200">Sistem Informasi Desa</p>
                            <h1 class="mt-5 text-4xl font-semibold leading-tight text-white">
                                Kelola data, layanan, dan publikasi desa dalam satu ruang kerja.
                            </h1>
                            <p class="mt-5 max-w-lg text-base leading-7 text-slate-300">
                                Akses admin dibuat ringkas untuk menjaga pekerjaan harian tetap cepat, jelas, dan tertata.
                            </p>
                        </div>

                        <div class="grid gap-4 text-sm text-slate-300">
                            <div class="flex items-center gap-3">
                                <span class="h-px w-10 bg-emerald-300"></span>
                                <span>{{ $guestLocation ?: 'Dashboard administrasi desa' }}</span>
                            </div>
                            <div class="flex gap-3 text-xs uppercase text-slate-500">
                                <span>Layanan</span>
                                <span>Data</span>
                                <span>Publikasi</span>
                            </div>
                        </div>
                    </div>
                </aside>

                <main class="flex min-h-full items-center justify-center px-4 py-8 sm:px-6 lg:px-12">
                    <div class="w-full max-w-md">
                        <a href="/" class="mb-6 flex items-center gap-3 lg:hidden">
                            <span class="grid h-11 w-11 place-items-center rounded-lg bg-white text-emerald-700 shadow-sm ring-1 ring-slate-200">
                                <x-application-logo class="h-7 w-7" />
                            </span>
                            <span>
                                <span class="block text-xs font-semibold uppercase text-emerald-700">Portal Desa</span>
                                <span class="mt-1 block text-base font-semibold leading-tight text-slate-950">{{ $guestVillageName }}</span>
                            </span>
                        </a>

                        <div class="rounded-lg border border-white/80 bg-white/95 p-6 shadow-xl shadow-slate-200/70 sm:p-8">
                            {{ $slot }}
                        </div>

                        <p class="mt-6 text-center text-xs text-slate-500">
                            &copy; {{ now()->year }} {{ $guestVillageName }}. Akses terbatas untuk petugas berwenang.
                        </p>
                    </div>
                </main>
            </div>
        </div>
    </body>
</html>
