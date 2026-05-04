<x-guest-layout>
    <div class="mb-6">
        <p class="text-sm font-semibold text-emerald-700">Konfirmasi Keamanan</p>
        <h2 class="mt-2 text-2xl font-semibold leading-tight text-slate-950">Masukkan password</h2>
        <p class="mt-2 text-sm leading-6 text-slate-500">
            Area ini membutuhkan konfirmasi ulang sebelum perubahan dilanjutkan.
        </p>
    </div>

    <form method="POST" action="{{ route('password.confirm') }}" class="space-y-5">
        @csrf

        <div>
            <x-input-label for="password" value="Password" />
            <x-text-input id="password" class="mt-2 block w-full" type="password" name="password" required autocomplete="current-password" placeholder="Masukkan password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <x-primary-button class="w-full py-3">
            Konfirmasi
        </x-primary-button>
    </form>
</x-guest-layout>
