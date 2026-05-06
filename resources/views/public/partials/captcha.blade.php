<div class="rounded-lg border border-emerald-100 bg-emerald-50 p-4">
    <label for="captcha_answer" class="block text-sm font-semibold text-slate-800">
        Verifikasi: {{ $captcha['question'] }}
    </label>
    <input
        id="captcha_answer"
        name="{{ $captcha['field'] }}"
        type="text"
        inputmode="numeric"
        autocomplete="off"
        class="mt-2 block w-full rounded-lg border-slate-200 bg-white px-4 py-3 text-sm text-slate-900 shadow-sm focus:border-emerald-500 focus:ring-emerald-500"
        placeholder="Jawaban"
        required
    >
    @error('captcha_answer')
        <p class="mt-2 text-sm font-semibold text-rose-600">{{ $message }}</p>
    @enderror
</div>
