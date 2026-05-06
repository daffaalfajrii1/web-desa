{{--
    Variabel: $village (opsional), serta boleh kirim:
        $wrapClass, $imgClass, $fallbackLogoClass untuk styling kotak logo.
--}}
@php
    $bm_wrap_class ??= 'h-11 w-11 shrink-0 overflow-hidden rounded-lg bg-emerald-50 ring-1 ring-emerald-100';
    $bm_img_class ??= 'h-full w-full object-contain p-1';
    $bm_fallback_class ??= 'h-8 w-8';
@endphp

@if ($village?->logo_url ?? false)
    <span class="grid place-items-center {{ $bm_wrap_class }}">
        <img src="{{ $village->logo_url }}" alt="" class="{{ $bm_img_class }}">
    </span>
@else
    <span class="grid place-items-center {{ $bm_wrap_class }} text-emerald-700">
        <x-application-logo class="{{ $bm_fallback_class }}" />
    </span>
@endif
