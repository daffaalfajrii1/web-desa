<footer class="border-t border-emerald-900/10 bg-white">
    <div class="mx-auto grid max-w-7xl gap-8 px-4 py-10 sm:px-6 lg:grid-cols-[1.2fr_0.8fr_0.8fr] lg:px-6">
        <div>
            <div class="flex items-center gap-3">
                @include('public.partials.brand-mark', ['village' => $village])
                <div>
                    <p class="text-sm font-semibold text-emerald-700">Website Resmi Desa</p>
                    <h2 class="text-lg font-bold text-slate-950">{{ $villageName }}</h2>
                </div>
            </div>
            <p class="mt-4 max-w-xl text-sm leading-6 text-slate-600">
                {{ $village?->address ?: ($villageLocation ?: 'Pusat informasi dan layanan publik desa.') }}
            </p>
        </div>

        <div>
            <h3 class="text-sm font-bold text-slate-950">Kontak</h3>
            <div class="mt-4 space-y-2 text-sm text-slate-600">
                <p>{{ $village?->phone ?: 'Telepon belum diatur' }}</p>
                <p>{{ $village?->email ?: 'Email belum diatur' }}</p>
                <p>{{ $village?->whatsapp ?: 'WhatsApp belum diatur' }}</p>
            </div>
        </div>

        <div>
            <h3 class="text-sm font-bold text-slate-950">Akses</h3>
            <div class="mt-4 grid gap-2 text-sm">
                <a class="text-slate-600 transition hover:text-emerald-700" href="{{ route('public.complaints.create') }}">Pengaduan</a>
                <a class="text-slate-600 transition hover:text-emerald-700" href="{{ route('public.self-services.index') }}">Layanan Mandiri</a>
                <a class="text-slate-600 transition hover:text-emerald-700" href="{{ route('public.attendance.index') }}">Absensi Pegawai</a>
                <a class="text-slate-600 transition hover:text-emerald-700" href="{{ route('login') }}">Login Admin</a>
            </div>
        </div>
    </div>
    <div class="border-t border-slate-100 px-4 py-4 text-center text-xs text-slate-500">
        &copy; {{ now()->year }} {{ $villageName }}. Semua hak dilindungi.
    </div>
</footer>
