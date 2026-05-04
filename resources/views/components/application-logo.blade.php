@php
    $appLogoSetting = null;

    try {
        if (\Illuminate\Support\Facades\Schema::hasTable('village_settings')) {
            $appLogoSetting = \App\Models\VillageSetting::query()->first();
        }
    } catch (\Throwable $exception) {
        $appLogoSetting = null;
    }

    $appLogoName = $appLogoSetting?->village_name ?: config('app.name', 'Web Desa');
    $appLogoUrl = $appLogoSetting?->logo_url;
    $logoClass = trim(($attributes->get('class') ?: 'block h-10 w-auto').' object-contain');
    $logoAttributes = $attributes->except('class')->merge(['class' => $logoClass]);
@endphp

@if ($appLogoUrl)
    <img src="{{ $appLogoUrl }}" alt="Logo {{ $appLogoName }}" {{ $logoAttributes }}>
@else
    <svg viewBox="0 0 64 64" fill="none" xmlns="http://www.w3.org/2000/svg" aria-label="Logo {{ $appLogoName }}" {{ $logoAttributes }}>
        <rect x="8" y="10" width="48" height="44" rx="10" fill="currentColor" class="text-emerald-700" />
        <path d="M18 34L32 22L46 34V48H18V34Z" fill="white" />
        <path d="M25 48V37H39V48" stroke="#047857" stroke-width="4" stroke-linecap="round" stroke-linejoin="round" />
        <path d="M20 28C20 21.373 25.373 16 32 16C38.627 16 44 21.373 44 28" stroke="#FBBF24" stroke-width="4" stroke-linecap="round" />
    </svg>
@endif
