<header class="sticky top-0 z-40 hidden border-b border-emerald-900/10 bg-white/92 backdrop-blur lg:block">
    <nav class="mx-auto flex h-20 max-w-7xl items-center justify-between px-6">
        <a href="{{ route('home') }}" class="flex min-w-0 items-center gap-3">
            @include('public.partials.brand-mark', ['village' => $village])
            <span class="min-w-0">
                <span class="block truncate text-sm font-semibold text-emerald-700">Website Resmi Desa</span>
                <span class="block truncate text-base font-bold text-slate-950">{{ $villageName }}</span>
            </span>
        </a>

        <div class="flex items-center gap-1">
            <a href="{{ route('home') }}" class="frontend-nav-link {{ request()->routeIs('home') ? 'is-active' : '' }}">Beranda</a>

            <details class="nav-profile-dropdown relative [&_summary::-webkit-details-marker]:hidden">
                <summary class="frontend-nav-link inline-flex cursor-pointer list-none select-none items-center gap-1 {{ request()->routeIs('public.profile*') ? 'is-active' : '' }}">
                    <span>Profil</span>
                    <svg class="nav-profile-chevron h-4 w-4 shrink-0 opacity-70 transition-transform duration-200" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M6 9l6 6 6-6"/></svg>
                </summary>
                <div class="absolute left-0 top-full z-50 mt-1 min-w-[220px] rounded-lg border border-slate-200 bg-white py-2 shadow-lg">
                    <a href="{{ route('public.profile') }}" class="block px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-emerald-50 hover:text-emerald-900">Ringkasan profil</a>
                    <a href="{{ route('public.profile.structure') }}" class="block px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-emerald-50 hover:text-emerald-900">Struktur organisasi</a>
                    @foreach (($profileNavMenus ?? collect()) as $pmenu)
                        <a href="{{ route('public.profile.menu', $pmenu->slug) }}" class="block px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-emerald-50 hover:text-emerald-900">{{ $pmenu->title }}</a>
                    @endforeach
                    @foreach (($profileNavOrphanPages ?? collect()) as $profilePage)
                        <a href="{{ route('public.profile.page', $profilePage->slug) }}" class="block px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-emerald-50 hover:text-emerald-900">{{ $profilePage->title }}</a>
                    @endforeach
                    <a href="{{ route('public.map') }}" class="block px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-emerald-50 hover:text-emerald-900">Peta desa</a>
                </div>
            </details>

            <a href="{{ route('public.services.index') }}" class="frontend-nav-link {{ request()->routeIs('public.services.*') || request()->routeIs('public.self-services.*') ? 'is-active' : '' }}">Layanan</a>
            <a href="{{ route('public.posts.index') }}" class="frontend-nav-link {{ request()->routeIs('public.posts.*') ? 'is-active' : '' }}">Berita</a>
            <a href="{{ route('public.public-informations.index') }}" class="frontend-nav-link {{ request()->routeIs('public.public-informations.*') ? 'is-active' : '' }}">Informasi</a>
            <a href="{{ route('public.infographics.index') }}" class="frontend-nav-link {{ request()->routeIs('public.infographics.*') ? 'is-active' : '' }}">Infografis</a>
            <a href="{{ route('public.shops.index') }}" class="frontend-nav-link {{ request()->routeIs('public.shops.*') ? 'is-active' : '' }}">Lapak</a>
            <a href="{{ route('public.tourism.index') }}" class="frontend-nav-link {{ request()->routeIs('public.tourism.*') ? 'is-active' : '' }}">Wisata</a>
        </div>

        <div class="flex items-center gap-2">
            <form method="GET" action="{{ route('public.search') }}" class="hidden xl:flex xl:items-center">
                <label class="relative block">
                    <span class="pointer-events-none absolute inset-y-0 left-3 flex items-center text-slate-400">
                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M21 21l-4.35-4.35M10.5 18a7.5 7.5 0 110-15 7.5 7.5 0 010 15z"/></svg>
                    </span>
                    <input type="search" name="q" value="{{ request('q') }}" placeholder="Cari..." class="h-10 w-40 rounded-lg border-slate-200 pl-9 pr-3 text-sm shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                </label>
            </form>
            <a href="{{ route('public.complaints.create') }}" class="inline-flex items-center justify-center rounded-lg border border-rose-100 bg-rose-50 px-3 py-2 text-xs font-semibold text-rose-900 transition hover:bg-rose-100">
                Pengaduan
            </a>
            <a href="{{ route('public.attendance.index') }}" class="inline-flex items-center justify-center rounded-lg border border-emerald-100 bg-emerald-50 px-4 py-2 text-sm font-semibold text-emerald-800 transition hover:bg-emerald-100">
                Absensi
            </a>
            <a href="{{ route('login') }}" class="inline-flex items-center justify-center rounded-lg bg-slate-950 px-4 py-2 text-sm font-semibold text-white transition hover:bg-slate-800">
                Login Admin
            </a>
        </div>
    </nav>
</header>

@once
    @push('scripts')
        <script>
            document.addEventListener('click', function (e) {
                document.querySelectorAll('.nav-profile-dropdown[open]').forEach(function (el) {
                    if (! el.contains(e.target)) {
                        el.removeAttribute('open');
                    }
                });
            });
        </script>
    @endpush
@endonce
