@php
    $siteKey = config('services.recaptcha.site_key');
@endphp

@if ($siteKey)
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-inner ring-1 ring-slate-100">
        <p class="mb-4 text-xs font-semibold uppercase tracking-[0.12em] text-slate-500">Verifikasi keamanan</p>
        <div class="g-recaptcha overflow-x-auto rounded-lg bg-slate-50/70 p-2 ring-1 ring-slate-100" data-sitekey="{{ $siteKey }}" data-theme="light"></div>
        @error('g-recaptcha-response')
            <p class="mt-3 text-sm font-semibold text-rose-600">{{ $message }}</p>
        @enderror
    </div>
@endif
