@extends('public.layouts.app')

@section('title', 'Struktur organisasi')

@section('content')
<section class="bg-emerald-950 py-8 text-white sm:py-10">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-6">
        <a href="{{ route('public.profile') }}" class="inline-flex text-sm font-semibold text-amber-200 hover:text-amber-100">← Ringkasan profil</a>
        <p class="mt-4 text-xs font-extrabold uppercase tracking-[0.2em] text-emerald-200/90">Perangkat desa</p>
        <h1 class="mt-2 text-3xl font-extrabold tracking-tight sm:text-4xl">Struktur organisasi</h1>
        <p class="mt-3 max-w-2xl text-sm leading-relaxed text-emerald-100/95">
            Daftar perangkat desa diurutkan mengikuti urutan jabatan resmi.
        </p>
    </div>
</section>

<section class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-6 lg:py-14">
    @php
        $hasVisible = $unassigned->isNotEmpty()
            || $positions->contains(fn ($p) => $p->employees->isNotEmpty());
    @endphp

    @if (! $hasVisible)
        <div class="frontend-empty">Data struktur organisasi belum dipublikasikan.</div>
    @else
        <div class="space-y-12">
            @foreach ($positions as $position)
                @if ($position->employees->isEmpty())
                    @continue
                @endif
                <div>
                    <div class="mb-5 flex flex-wrap items-end justify-between gap-3 border-b border-emerald-100 pb-3">
                        <div>
                            <h2 class="text-xl font-extrabold text-slate-950">{{ $position->name }}</h2>
                        </div>
                    </div>
                    <div class="grid gap-5 sm:grid-cols-2 lg:grid-cols-3">
                        @foreach ($position->employees as $employee)
                            @include('public.pages._structure-member-card', ['employee' => $employee, 'imageUrl' => $imageUrl])
                        @endforeach
                    </div>
                </div>
            @endforeach

            @if ($unassigned->isNotEmpty())
                <div>
                    <div class="mb-5 border-b border-slate-200 pb-3">
                        <h2 class="text-xl font-extrabold text-slate-950">Perangkat lainnya</h2>
                        <p class="mt-1 text-sm text-slate-500">Perangkat dengan penempatan jabatan mandiri.</p>
                    </div>
                    <div class="grid gap-5 sm:grid-cols-2 lg:grid-cols-3">
                        @foreach ($unassigned as $employee)
                            @include('public.pages._structure-member-card', ['employee' => $employee, 'imageUrl' => $imageUrl])
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    @endif
</section>

<dialog id="sotk_member_modal" class="public-media-lightbox" aria-labelledby="sotk-modal-name">
    <div class="w-[min(92vw,680px)] rounded-2xl bg-white p-6 shadow-2xl ring-1 ring-slate-200 sm:p-8">
        <div class="flex items-start justify-between gap-3">
            <p class="text-xs font-extrabold uppercase tracking-[0.14em] text-emerald-700">Detail Perangkat Desa</p>
            <button type="button" id="sotk_modal_close" class="rounded-lg border border-slate-200 px-3 py-1.5 text-sm font-semibold text-slate-600 hover:bg-slate-50">Tutup</button>
        </div>
        <div class="mt-5 grid gap-5 sm:grid-cols-[170px_1fr] sm:items-start">
            <div class="mx-auto h-40 w-40 overflow-hidden rounded-2xl bg-gradient-to-br from-emerald-100 to-slate-100 ring-1 ring-slate-200 sm:mx-0">
                <img id="sotk-modal-photo" src="" alt="" class="h-full w-full object-cover">
            </div>
            <div>
                <h3 id="sotk-modal-name" class="text-2xl font-extrabold text-slate-950"></h3>
                <p id="sotk-modal-position" class="mt-1 text-sm font-semibold text-emerald-700"></p>
                <div id="sotk-modal-socials" class="mt-4 flex flex-wrap gap-2"></div>
            </div>
        </div>
    </div>
</dialog>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var modal = document.getElementById('sotk_member_modal');
            var closeBtn = document.getElementById('sotk_modal_close');
            var nameEl = document.getElementById('sotk-modal-name');
            var posEl = document.getElementById('sotk-modal-position');
            var photoEl = document.getElementById('sotk-modal-photo');
            var socialsEl = document.getElementById('sotk-modal-socials');

            function socialButton(label, href) {
                if (!href) {
                    return '';
                }
                return '<a href="' + href + '" target="_blank" rel="noopener noreferrer" class="inline-flex items-center rounded-lg border border-slate-200 bg-white px-3 py-1.5 text-xs font-semibold text-slate-700 transition hover:border-emerald-200 hover:bg-emerald-50">' + label + '</a>';
            }

            function closeModal() {
                if (modal?.open) {
                    modal.close();
                }
            }

            document.querySelectorAll('[data-sotk-card]').forEach(function (card) {
                card.addEventListener('click', function () {
                    nameEl.textContent = card.dataset.name || '-';
                    posEl.textContent = card.dataset.position || '-';
                    photoEl.src = card.dataset.photo || '';
                    photoEl.alt = card.dataset.name || '';
                    socialsEl.innerHTML = [
                        socialButton('WhatsApp', card.dataset.wa),
                        socialButton('Facebook', card.dataset.facebook),
                        socialButton('Instagram', card.dataset.instagram),
                        socialButton('X', card.dataset.x),
                        socialButton('YouTube', card.dataset.youtube),
                        socialButton('Telegram', card.dataset.telegram),
                    ].filter(Boolean).join('');
                    if (typeof modal.showModal === 'function') {
                        modal.showModal();
                    }
                });
            });

            closeBtn?.addEventListener('click', closeModal);
            modal?.addEventListener('click', function (e) {
                if (e.target === modal) {
                    closeModal();
                }
            });
        });
    </script>
@endpush
