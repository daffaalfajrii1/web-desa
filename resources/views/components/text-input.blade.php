@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'rounded-lg border-slate-200 bg-white px-4 py-3 text-sm text-slate-900 shadow-sm transition duration-150 placeholder:text-slate-400 focus:border-emerald-500 focus:ring-emerald-500 disabled:bg-slate-100 disabled:text-slate-500']) }}>
