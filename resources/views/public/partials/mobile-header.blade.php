<header class="sticky top-0 z-40 border-b border-emerald-900/10 bg-white/94 backdrop-blur lg:hidden">
    <div class="flex min-h-16 items-center justify-between px-4 py-3">
        <a href="{{ route('home') }}" class="flex min-w-0 items-center gap-3">
            @include('public.partials.brand-mark', [
                'village' => $village,
                'bm_wrap_class' => 'h-10 w-10 shrink-0 overflow-hidden rounded-lg bg-emerald-50 ring-1 ring-emerald-100',
                'bm_img_class' => 'h-full w-full object-contain p-1',
                'bm_fallback_class' => 'h-7 w-7',
            ])
            <span class="min-w-0">
                <span class="block truncate text-xs font-semibold text-emerald-700">Website Resmi Desa</span>
                <span class="block truncate text-sm font-bold text-slate-950">{{ $villageName }}</span>
                @if ($villageLocation)
                    <span class="block truncate text-xs text-slate-500">{{ $villageLocation }}</span>
                @endif
            </span>
        </a>

        <div class="flex items-center gap-2">
            <a href="{{ route('public.search') }}" class="inline-flex h-10 items-center justify-center rounded-lg bg-emerald-50 px-3 text-xs font-semibold text-emerald-800 ring-1 ring-emerald-100">
                Cari
            </a>
            <a href="{{ route('login') }}" class="inline-flex h-10 items-center justify-center rounded-lg bg-slate-950 px-3 text-xs font-semibold text-white">
                Login
            </a>
        </div>
    </div>
</header>
