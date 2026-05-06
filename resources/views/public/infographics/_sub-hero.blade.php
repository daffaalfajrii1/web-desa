@props(['title', 'subtitle' => null, 'kicker' => 'Infografis'])

<section class="bg-emerald-950 py-10 text-white">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-6">
        <nav class="text-xs font-semibold text-amber-200/90" aria-label="Breadcrumb">
            <a href="{{ route('public.infographics.index') }}" class="hover:text-amber-100">Infografis</a>
            <span class="mx-2 opacity-60">/</span>
            <span class="text-white">{{ $title }}</span>
        </nav>
        <p class="mt-3 text-sm font-semibold text-amber-200">{{ $kicker }}</p>
        <h1 class="mt-1 text-3xl font-extrabold sm:text-4xl">{{ $title }}</h1>
        @if ($subtitle)
            <p class="mt-3 max-w-3xl text-sm leading-6 text-emerald-50">{{ $subtitle }}</p>
        @endif
    </div>
</section>
