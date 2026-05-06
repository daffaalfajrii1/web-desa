@php
    $themeService = app(\App\Services\Public\ThemeService::class);
    $village = $village ?? $themeService->villageSetting();
    $theme = $theme ?? $themeService->resolve($village);
    $villageName = $village?->village_name ?: config('app.name', 'Web Desa');
    $villageLocation = collect([$village?->district_name, $village?->regency_name, $village?->province_name])->filter()->implode(', ');
    $hasSiteIcon = $village && ($village->favicon || $village->logo_path || $village->logo);
    $iconBuster = (string) (($village?->updated_at?->timestamp) ?: time());
    $tabIconUrl = $village?->logo_url;
    if ($tabIconUrl && \Illuminate\Support\Str::startsWith($tabIconUrl, '/')) {
        $tabIconUrl = url($tabIconUrl);
    }
    $tabIconUrl = $tabIconUrl ? $tabIconUrl.(str_contains($tabIconUrl, '?') ? '&' : '?').'v='.$iconBuster : null;
@endphp

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta name="theme-color" content="#047857">

        <title>@hasSection('title')@yield('title') - {{ $villageName }}@else{{ $villageName }}@endif</title>
        @if ($hasSiteIcon && $tabIconUrl)
            <link rel="icon" href="{{ $tabIconUrl }}" sizes="any">
            <link rel="shortcut icon" href="{{ $tabIconUrl }}">
            <link rel="apple-touch-icon" href="{{ $tabIconUrl }}">
        @endif

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])
        @stack('head')
    </head>
    <body
        class="frontend-public {{ $theme['class'] }} {{ $theme['choice_class'] }} min-h-screen bg-[var(--theme-bg)] text-[var(--theme-text)] antialiased"
        data-theme-active="{{ $theme['active'] }}"
        data-theme-requested="{{ $theme['requested'] }}"
    >
        @include('public.partials.preloader', ['villageName' => $villageName, 'village' => $village])

        <div class="min-h-screen pb-24 lg:pb-0">
            @include('public.partials.navbar', compact('village', 'villageName', 'villageLocation'))
            @include('public.partials.mobile-header', compact('village', 'villageName', 'villageLocation'))

            <main id="main-content" tabindex="-1">
                @yield('content')
            </main>

            @include('public.partials.footer', compact('village', 'villageName', 'villageLocation'))
        </div>

        @include('public.partials.visit-widget')
        @include('public.partials.accessibility-widget')
        @include('public.partials.mobile-bottom-nav')

        @stack('scripts')
    </body>
</html>
