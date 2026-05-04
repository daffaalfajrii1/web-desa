<x-guest-layout>
    <div class="mb-6">
        <p class="text-sm font-semibold text-emerald-700">Pemulihan Akun</p>
        <h2 class="mt-2 text-2xl font-semibold leading-tight text-slate-950">Lupa password?</h2>
        <p class="mt-2 text-sm leading-6 text-slate-500">
            Masukkan email akun admin. Tautan reset password akan dikirim ke email tersebut.
        </p>
    </div>

    <x-auth-session-status class="mb-5" :status="session('status')" />

    <form method="POST" action="{{ route('password.email') }}" class="space-y-5">
        @csrf

        <div>
            <x-input-label for="email" value="Email" />
            <x-text-input id="email" class="mt-2 block w-full" type="email" name="email" :value="old('email')" required autofocus placeholder="nama@email.com" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <x-primary-button class="w-full py-3">
            Kirim Link Reset
        </x-primary-button>
    </form>

    <p class="mt-6 text-center text-sm text-slate-500">
        Sudah ingat password?
        <a class="font-semibold text-emerald-700 transition hover:text-emerald-900 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2" href="{{ route('login') }}">
            Kembali masuk
        </a>
    </p>
</x-guest-layout>
