@php
    $items = [
        ['label' => 'Beranda', 'href' => route('home'), 'active' => request()->routeIs('home'), 'icon' => 'M3 11.5L12 4l9 7.5V21a1 1 0 01-1 1h-5v-6H9v6H4a1 1 0 01-1-1v-9.5z'],
        ['label' => 'Peta', 'href' => route('public.map'), 'active' => request()->routeIs('public.map'), 'icon' => 'M9 18l-6 3V6l6-3 6 3 6-3v15l-6 3-6-3z'],
        ['label' => 'Menu', 'href' => route('home') . '#menu-desa', 'active' => false, 'icon' => 'M4 5h6v6H4V5zm10 0h6v6h-6V5zM4 13h6v6H4v-6zm10 0h6v6h-6v-6z'],
        ['label' => 'Login', 'href' => route('login'), 'active' => request()->routeIs('login'), 'icon' => 'M10 17l5-5-5-5v3H3v4h7v3zm4 4h5a2 2 0 002-2V5a2 2 0 00-2-2h-5'],
        ['label' => 'Galeri', 'href' => route('public.galleries.index'), 'active' => request()->routeIs('public.galleries.*'), 'icon' => 'M4 5h16v14H4V5zm3 10l3-4 2 3 2-2 3 3H7z'],
    ];
@endphp

<nav class="fixed inset-x-0 bottom-0 z-50 border-t border-emerald-900/10 bg-white/96 px-2 pb-[max(0.75rem,env(safe-area-inset-bottom))] pt-2 shadow-[0_-12px_30px_rgba(15,23,42,0.08)] backdrop-blur lg:hidden" aria-label="Navigasi bawah">
    <div class="mx-auto grid max-w-md grid-cols-5 gap-1">
        @foreach ($items as $item)
            <a href="{{ $item['href'] }}" class="mobile-bottom-link {{ $item['active'] ? 'is-active' : '' }}">
                <svg viewBox="0 0 24 24" class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <path d="{{ $item['icon'] }}" />
                </svg>
                <span>{{ $item['label'] }}</span>
            </a>
        @endforeach
    </div>
</nav>
