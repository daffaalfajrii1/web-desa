@php
    $menus = [
        ['label' => 'Layanan', 'href' => route('public.services.index'), 'tone' => 'emerald', 'icon' => 'M12 6v12m6-6H6'],
        ['label' => 'Infografis', 'href' => route('public.infographics.index'), 'tone' => 'teal', 'icon' => 'M4 19V9M12 19V5M20 19v-7'],
        ['label' => 'Peta Desa', 'href' => route('public.map'), 'tone' => 'sky', 'icon' => 'M9 18l-6 3V6l6-3 6 3 6-3v15l-6 3-6-3z'],
        ['label' => 'Produk Hukum', 'href' => route('public.legal-products.index'), 'tone' => 'amber', 'icon' => 'M7 4h8l4 4v12H7V4zm8 0v5h4'],
        ['label' => 'Informasi Publik', 'href' => route('public.public-informations.index'), 'tone' => 'indigo', 'icon' => 'M12 20h9M12 4h9M4 9h17M4 15h17'],
        ['label' => 'PPID', 'href' => route('public.ppid.index'), 'tone' => 'cyan', 'icon' => 'M6 4h12v16H6zM9 9h10M9 13h6'],
        ['label' => 'Lapak', 'href' => route('public.shops.index'), 'tone' => 'rose', 'icon' => 'M5 8h14l-1 12H6L5 8zm2 0a5 5 0 0110 0'],
        ['label' => 'Wisata', 'href' => route('public.tourism.index'), 'tone' => 'orange', 'icon' => 'M4 10l8-6 8 6v10a1 1 0 01-1 1h-5v-6H10v6H5a1 1 0 01-1-1V10z'],
        ['label' => 'Berita', 'href' => route('public.posts.index'), 'tone' => 'violet', 'icon' => 'M5 4h14v16H5zM8 8h8M8 12h5'],
        ['label' => 'Galeri', 'href' => route('public.galleries.index'), 'tone' => 'fuchsia', 'icon' => 'M4 5h16v14H4zM7 14l3-4 2 3 3-3 3 4'],
        ['label' => 'Pengaduan', 'href' => route('public.complaints.create'), 'tone' => 'red', 'icon' => 'M21 11.5a8.38 8.38 0 01-.9 3.8 8.5 8.5 0 01-7.6 4.7 8.38 8.38 0 01-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 01-.9-3.8 8.5 8.5 0 014.7-7.6'],
        ['label' => 'Absensi', 'href' => route('public.attendance.index'), 'tone' => 'lime', 'icon' => 'M8 7V4m8 3V4M5 10h14M6 5h12a1 1 0 011 1v13H5V6a1 1 0 011-1z'],
        ['label' => 'Mandiri', 'href' => route('public.self-services.index'), 'tone' => 'emerald', 'icon' => 'M12 11c2.2 0 4-1.8 4-4s-1.8-4-4-4-4 1.8-4 4 1.8 4 4 4zm0 2c-3.3 0-6 2.2-6 5v3h12v-3c0-2.8-2.7-5-6-5z'],
        ['label' => 'Cek Layanan', 'href' => route('public.self-services.status'), 'tone' => 'sky', 'icon' => 'M9 5H7a2 2 0 00-2 2v12h14V7a2 2 0 00-2-2h-2M9 5a3 3 0 006 0M9 14l2 2 4-4'],
        ['label' => 'Cari', 'href' => route('public.search'), 'tone' => 'cyan', 'icon' => 'M21 21l-4.35-4.35M10.5 18a7.5 7.5 0 110-15 7.5 7.5 0 010 15z'],
        ['label' => 'Login Admin', 'href' => route('login'), 'tone' => 'slate', 'icon' => 'M10 17l5-5-5-5v3H3v4h7v3zm8 2H14'],
    ];
@endphp

<section id="menu-desa" class="mx-auto max-w-7xl">
    <div class="mb-6 flex flex-wrap items-end justify-between gap-3 border-b border-emerald-100/80 pb-4">
        <div>
            <p class="text-xs font-bold uppercase tracking-[0.14em] text-emerald-600">Akses cepat</p>
            <h2 class="mt-1.5 text-xl font-extrabold tracking-tight text-slate-950 sm:text-2xl">Menu layanan desa</h2>
            <p class="mt-1 max-w-xl text-sm text-slate-600">Pintasan ke layanan, dokumen, dan kanal partisipasi warga.</p>
        </div>
        <a href="{{ route('public.services.index') }}" class="inline-flex shrink-0 items-center gap-1 rounded-lg bg-emerald-700 px-4 py-2 text-sm font-bold text-white shadow-sm transition hover:bg-emerald-800">Semua layanan <span aria-hidden="true">→</span></a>
    </div>

    <div class="grid grid-cols-3 gap-3 sm:grid-cols-4 lg:grid-cols-7">
        @foreach ($menus as $menu)
            <a href="{{ $menu['href'] }}" class="quick-menu-card group">
                <span class="quick-menu-icon quick-menu-{{ $menu['tone'] }}">
                    <svg viewBox="0 0 24 24" class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="{{ $menu['icon'] }}" />
                    </svg>
                </span>
                <span class="mt-3 block text-center text-[11px] font-bold leading-4 text-slate-700 sm:text-xs">{{ $menu['label'] }}</span>
            </a>
        @endforeach
    </div>
</section>
