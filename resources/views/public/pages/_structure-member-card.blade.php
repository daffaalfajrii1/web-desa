@php
    $photo = $imageUrl($employee->photo ?? null);
    $jobTitle = $employee->employeePosition?->name ?? $employee->position ?: 'Perangkat desa';
    $waRaw = trim((string) ($employee->whatsapp ?? ''));
    $waDigits = preg_replace('/\D+/', '', $waRaw);
    if (str_starts_with($waDigits, '0')) {
        $waDigits = '62'.substr($waDigits, 1);
    } elseif ($waDigits !== '' && ! str_starts_with($waDigits, '62')) {
        $waDigits = '62'.$waDigits;
    }
    $socials = collect([
        $waDigits !== '' ? ['href' => 'https://wa.me/'.$waDigits, 'label' => 'WhatsApp', 'tone' => 'emerald'] : null,
        filled($employee->facebook) ? ['href' => str_starts_with($employee->facebook, 'http') ? $employee->facebook : 'https://'.$employee->facebook, 'label' => 'Facebook', 'tone' => 'sky'] : null,
        filled($employee->instagram) ? ['href' => str_starts_with($employee->instagram, 'http') ? $employee->instagram : 'https://instagram.com/'.ltrim($employee->instagram, '@/'), 'label' => 'Instagram', 'tone' => 'fuchsia'] : null,
        filled($employee->twitter) ? ['href' => str_starts_with($employee->twitter, 'http') ? $employee->twitter : 'https://twitter.com/'.ltrim($employee->twitter, '@/'), 'label' => 'X', 'tone' => 'slate'] : null,
        filled($employee->youtube) ? ['href' => str_starts_with($employee->youtube, 'http') ? $employee->youtube : 'https://youtube.com/'.ltrim($employee->youtube, '@/'), 'label' => 'YouTube', 'tone' => 'red'] : null,
        filled($employee->telegram) ? ['href' => str_starts_with($employee->telegram, 'http') ? $employee->telegram : 'https://t.me/'.ltrim($employee->telegram, '@/'), 'label' => 'Telegram', 'tone' => 'cyan'] : null,
    ])->filter()->values();

@endphp
<button
    type="button"
    data-sotk-card
    data-name="{{ $employee->name }}"
    data-position="{{ $jobTitle }}"
    data-photo="{{ $photo }}"
    data-wa="{{ $waDigits !== '' ? 'https://wa.me/'.$waDigits : '' }}"
    data-facebook="{{ filled($employee->facebook) ? (str_starts_with($employee->facebook, 'http') ? $employee->facebook : 'https://'.$employee->facebook) : '' }}"
    data-instagram="{{ filled($employee->instagram) ? (str_starts_with($employee->instagram, 'http') ? $employee->instagram : 'https://instagram.com/'.ltrim($employee->instagram, '@/')) : '' }}"
    data-x="{{ filled($employee->twitter) ? (str_starts_with($employee->twitter, 'http') ? $employee->twitter : 'https://twitter.com/'.ltrim($employee->twitter, '@/')) : '' }}"
    data-youtube="{{ filled($employee->youtube) ? (str_starts_with($employee->youtube, 'http') ? $employee->youtube : 'https://youtube.com/'.ltrim($employee->youtube, '@/')) : '' }}"
    data-telegram="{{ filled($employee->telegram) ? (str_starts_with($employee->telegram, 'http') ? $employee->telegram : 'https://t.me/'.ltrim($employee->telegram, '@/')) : '' }}"
    class="relative flex w-full items-center gap-4 overflow-hidden rounded-2xl border border-slate-200/90 bg-gradient-to-r from-white to-slate-50/90 p-4 text-left shadow-lg shadow-slate-900/[0.04] ring-1 ring-slate-100 transition hover:-translate-y-0.5 hover:shadow-xl hover:ring-emerald-200/70 sm:p-5"
>
    <div class="absolute inset-x-0 top-0 h-16 bg-gradient-to-r from-emerald-800 via-emerald-700 to-teal-800"></div>
    <div class="relative h-24 w-24 shrink-0 overflow-hidden rounded-2xl bg-white shadow-xl ring-4 ring-white sm:h-28 sm:w-28">
        @if ($photo)
            <img src="{{ $photo }}" alt="" class="h-full w-full object-cover">
        @else
            <span class="flex h-full w-full items-center justify-center bg-gradient-to-br from-emerald-100 to-slate-100 text-3xl font-extrabold text-emerald-800">{{ \Illuminate\Support\Str::substr($employee->name, 0, 1) }}</span>
        @endif
    </div>
    <span class="relative mt-12 min-w-0 flex-1">
        <span class="block text-[11px] font-extrabold uppercase tracking-[0.15em] text-emerald-700">{{ $jobTitle }}</span>
        <span class="mt-1 block text-lg font-extrabold leading-snug text-slate-950 sm:text-xl">{{ $employee->name }}</span>
        @if ($socials->isNotEmpty())
            <span class="mt-4 flex flex-wrap items-center gap-2">
                @foreach ($socials as $soc)
                    <span class="inline-flex h-8 w-8 items-center justify-center rounded-lg bg-slate-900 text-white shadow-sm ring-1 ring-slate-800/80">
                        @switch($soc['label'])
                            @case('WhatsApp')
                                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.123 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.435 9.884-9.884 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                                @break
                            @case('Facebook')
                                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                                @break
                            @case('Instagram')
                                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg>
                                @break
                            @case('X')
                                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M18.901 1.153h3.68l-8.04 9.19L24 22.846h-7.406l-5.8-7.584-6.638 7.584H.474l8.6-9.83L0 1.154h7.594l5.243 6.932zm-1.292 19.494h2.039L6.486 3.24H4.298z"/></svg>
                                @break
                            @case('YouTube')
                                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M23.498 6.186a3.016 3.016 0 00-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 00.502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 002.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 002.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/></svg>
                                @break
                            @case('Telegram')
                                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M11.944 0A12 12 0 0 0 0 12a12 12 0 0 0 12 12 12 12 0 0 0 12-12A12 12 0 0 0 12 0a12 12 0 0 0-.056 0zm4.962 7.224c.1-.002.321.023.465.14a.506.506 0 0 1 .171.325c.016.093.036.306.02.472-.18 1.898-.962 6.502-1.36 8.627-.168.9-.499 1.201-.82 1.23-.696.065-1.225-.46-1.9-.902-1.056-.693-1.653-1.124-2.678-1.8-1.185-.78-.417-1.21.258-1.91.177-.184 3.247-2.977 3.307-3.23.007-.032.014-.147-.056-.207s-.174-.041-.249-.024c-.106.024-1.793 1.14-5.061 3.345-.48.33-.913.49-1.302.48-.428-.008-1.252-.241-1.865-.44-.752-.245-1.349-.374-1.297-.789.027-.216.325-.437.893-.663 3.498-1.524 5.831-2.529 6.998-3.014 3.332-1.386 4.025-1.627 4.476-1.635z"/></svg>
                                @break
                            @default
                                <span class="text-[10px] font-bold">·</span>
                        @endswitch
                    </span>
                @endforeach
            </span>
        @endif
        @if ($employee->phone || $employee->email)
            <span class="mt-3 block text-xs text-slate-500">
                @if ($employee->phone)
                    <span class="block">Telp. {{ $employee->phone }}</span>
                @endif
                @if ($employee->email)
                    <span class="mt-1 block truncate text-emerald-700">{{ $employee->email }}</span>
                @endif
            </span>
        @endif
    </span>
</button>
