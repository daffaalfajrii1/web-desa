<x-guest-layout>
    <div class="mb-6">
        <p class="text-sm font-semibold text-emerald-700">Verifikasi Email</p>
        <h2 class="mt-2 text-2xl font-semibold leading-tight text-slate-950">Cek email Anda</h2>
        <p class="mt-2 text-sm leading-6 text-slate-500">
            Klik tautan verifikasi yang sudah dikirim. Jika belum masuk, kirim ulang tautan dari halaman ini.
        </p>
    </div>

    @if (session('status') == 'verification-link-sent')
        <div class="mb-5 rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-800">
            Tautan verifikasi baru sudah dikirim ke email yang terdaftar.
        </div>
    @endif

    <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <form method="POST" action="{{ route('verification.send') }}" class="w-full sm:w-auto">
            @csrf

            <x-primary-button class="w-full py-3 sm:w-auto">
                Kirim Ulang
            </x-primary-button>
        </form>

        <form method="POST" action="{{ route('logout') }}" class="w-full sm:w-auto">
            @csrf

            <x-secondary-button type="submit" class="w-full py-3 sm:w-auto">
                Keluar
            </x-secondary-button>
        </form>
    </div>
</x-guest-layout>
