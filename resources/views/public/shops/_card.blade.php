@php
    $detailUrl = route('public.shops.show', $shop->slug);
    $priceLabel = $shop->price !== null && (float) $shop->price > 0
        ? 'Rp '.number_format((float) $shop->price, 0, ',', '.')
        : 'Hubungi penjual';
    $waTemplate = 'Halo, aku melihat di lapak ini aku tertarik dengan produk "'.$shop->title.'".';
    $waTemplate .= ' Boleh minta info detail harga dan ketersediaan?';
    $waTemplate .= ' Link produk: '.$detailUrl;
    $waUrl = $shop->whatsapp_url
        ? $shop->whatsapp_url.(str_contains($shop->whatsapp_url, '?') ? '&' : '?').'text='.rawurlencode($waTemplate)
        : null;
@endphp

<article class="frontend-card group flex flex-col overflow-hidden transition hover:shadow-md">
    <a href="{{ $detailUrl }}" class="block">
        @if ($img)
            <div class="relative aspect-[4/3] overflow-hidden bg-slate-100">
                <img src="{{ $img }}" alt="{{ $shop->title }}" class="h-full w-full object-cover transition duration-300 group-hover:scale-105">
                @if ($shop->is_featured)
                    <span class="absolute left-3 top-3 rounded-full bg-amber-400 px-2 py-0.5 text-[10px] font-bold uppercase tracking-wide text-slate-950">Unggulan</span>
                @endif
            </div>
        @else
            <div class="grid aspect-[4/3] place-items-center bg-emerald-50 text-sm font-semibold text-emerald-700">Produk</div>
        @endif
    </a>

    <div class="flex flex-1 flex-col p-4">
        @if ($shop->category)
            <p class="text-xs font-semibold uppercase tracking-wide text-emerald-700">{{ $shop->category->name }}</p>
        @endif
        <a href="{{ $detailUrl }}" class="mt-1 line-clamp-2 text-base font-bold text-slate-950 hover:text-emerald-800">{{ $shop->title }}</a>
        @if ($shop->excerpt)
            <p class="mt-2 line-clamp-2 flex-1 text-sm text-slate-600">{{ $shop->excerpt }}</p>
        @else
            <div class="flex-1"></div>
        @endif

        <div class="mt-4 border-t border-slate-100 pt-3">
            <div class="flex items-center justify-between gap-2">
                <span class="text-sm font-extrabold text-emerald-800">{{ $priceLabel }}</span>
                @if ($shop->seller_name)
                    <span class="truncate text-xs font-medium text-slate-500">{{ $shop->seller_name }}</span>
                @endif
            </div>

            <div class="mt-3 grid gap-2 sm:grid-cols-2">
                <a href="{{ $detailUrl }}" class="rounded-lg bg-slate-100 px-3 py-2 text-center text-xs font-bold text-slate-700 transition hover:bg-slate-200">Detail</a>
                @if ($waUrl)
                    <a href="{{ $waUrl }}" target="_blank" rel="noopener" class="rounded-lg bg-emerald-700 px-3 py-2 text-center text-xs font-bold text-white transition hover:bg-emerald-800">Chat WA</a>
                @else
                    <span class="rounded-lg bg-slate-50 px-3 py-2 text-center text-xs font-bold text-slate-400">Kontak belum ada</span>
                @endif
            </div>
        </div>
    </div>
</article>
