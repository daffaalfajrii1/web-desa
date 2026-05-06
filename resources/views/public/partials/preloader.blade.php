<div id="site-preloader" class="fixed inset-0 z-[80] grid place-items-center bg-emerald-950 text-white transition duration-300">
    <div class="w-full max-w-sm px-6 text-center">
        <div class="mx-auto h-20 w-20 overflow-hidden rounded-lg bg-white shadow-xl shadow-emerald-950/30 ring-1 ring-white/20">
            @if (($village ?? null)?->logo_url ?? false)
                <img src="{{ $village->logo_url }}" alt="" class="h-full w-full object-contain p-4">
            @else
                <span class="grid h-full w-full place-items-center text-emerald-700">
                    <x-application-logo class="h-14 w-14" />
                </span>
            @endif
        </div>
        <p class="mt-5 text-sm font-semibold text-emerald-100">Website Resmi Desa</p>
        <h2 class="mt-2 text-2xl font-bold">{{ $villageName }}</h2>
        <p id="preloader-date" class="mt-3 text-sm text-emerald-100">{{ now()->translatedFormat('l, d F Y') }}</p>
        <div class="mx-auto mt-6 h-1.5 w-40 overflow-hidden rounded-lg bg-white/15">
            <span class="preloader-bar block h-full w-1/2 rounded-lg bg-amber-300"></span>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        var dateElement = document.getElementById('preloader-date');

        if (dateElement && window.Intl) {
            dateElement.textContent = new Intl.DateTimeFormat('id-ID', {
                weekday: 'long',
                day: '2-digit',
                month: 'long',
                year: 'numeric'
            }).format(new Date());
        }
    });

    window.addEventListener('load', function () {
        window.setTimeout(function () {
            var preloader = document.getElementById('site-preloader');

            if (! preloader) {
                return;
            }

            preloader.classList.add('is-hidden');
            window.setTimeout(function () {
                preloader.remove();
            }, 350);
        }, 450);
    });
</script>
