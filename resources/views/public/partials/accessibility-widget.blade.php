<div id="accessibility-widget" class="fixed bottom-44 right-4 z-50 lg:bottom-24 lg:right-6">
    <button type="button" class="accessibility-toggle grid h-12 w-12 place-items-center rounded-lg bg-slate-950 text-white shadow-lg shadow-slate-900/20 transition hover:bg-slate-800" aria-label="Buka alat aksesibilitas" data-accessibility-toggle>
        <svg viewBox="0 0 24 24" class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
            <path d="M12 4a2 2 0 100 4 2 2 0 000-4z" />
            <path d="M5 9h14M12 9v11M8 13l-2 7M16 13l2 7" />
        </svg>
    </button>

    <div class="accessibility-panel mt-3 hidden w-72 rounded-lg border border-slate-200 bg-white p-4 shadow-xl shadow-slate-900/15" data-accessibility-panel>
        <div class="flex items-center justify-between">
            <h2 class="text-sm font-bold text-slate-950">Aksesibilitas</h2>
            <button type="button" class="text-xs font-semibold text-emerald-700" data-accessibility-action="reset">Reset</button>
        </div>

        <div class="mt-4 grid grid-cols-2 gap-2 text-xs font-semibold">
            <button type="button" class="accessibility-action" data-accessibility-action="font-plus">Perbesar Teks</button>
            <button type="button" class="accessibility-action" data-accessibility-action="font-minus">Perkecil Teks</button>
            <button type="button" class="accessibility-action" data-accessibility-action="space-plus">Tambah Jarak</button>
            <button type="button" class="accessibility-action" data-accessibility-action="space-minus">Kurangi Jarak</button>
            <button type="button" class="accessibility-action" data-accessibility-action="line-plus">Tinggi Baris +</button>
            <button type="button" class="accessibility-action" data-accessibility-action="line-minus">Tinggi Baris -</button>
            <button type="button" class="accessibility-action" data-accessibility-action="invert">Balik Warna</button>
            <button type="button" class="accessibility-action" data-accessibility-action="grayscale">Abu-abu</button>
            <button type="button" class="accessibility-action" data-accessibility-action="underline">Underline Link</button>
            <button type="button" class="accessibility-action" data-accessibility-action="cursor">Kursor Besar</button>
            <button type="button" class="accessibility-action" data-accessibility-action="guide">Alat Baca</button>
            <button type="button" class="accessibility-action" data-accessibility-action="motion">Matikan Animasi</button>
        </div>
    </div>
</div>

<div id="reading-guide" class="pointer-events-none fixed left-0 right-0 z-[70] hidden h-10 border-y border-amber-300/70 bg-amber-200/20"></div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        var root = document.documentElement;
        var body = document.body;
        var toggle = document.querySelector('[data-accessibility-toggle]');
        var panel = document.querySelector('[data-accessibility-panel]');
        var guide = document.getElementById('reading-guide');
        var state = JSON.parse(localStorage.getItem('publicAccessibility') || '{}');

        state.font = state.font || 1;
        state.space = state.space || 0;
        state.line = state.line || 1;
        state.flags = state.flags || {};

        function clamp(value, min, max) {
            return Math.min(max, Math.max(min, value));
        }

        function applyState() {
            root.style.setProperty('--a11y-font-scale', state.font);
            root.style.setProperty('--a11y-word-space', state.space + 'px');
            root.style.setProperty('--a11y-line-scale', state.line);

            ['invert', 'grayscale', 'underline', 'cursor', 'motion'].forEach(function (flag) {
                body.classList.toggle('a11y-' + flag, Boolean(state.flags[flag]));
            });

            body.classList.toggle('a11y-guide', Boolean(state.flags.guide));
            localStorage.setItem('publicAccessibility', JSON.stringify(state));
        }

        function resetState() {
            state = { font: 1, space: 0, line: 1, flags: {} };
            applyState();
        }

        if (toggle && panel) {
            toggle.addEventListener('click', function () {
                panel.classList.toggle('hidden');
            });
        }

        document.querySelectorAll('[data-accessibility-action]').forEach(function (button) {
            button.addEventListener('click', function () {
                var action = button.dataset.accessibilityAction;

                if (action === 'reset') {
                    resetState();
                    return;
                }

                if (action === 'font-plus') state.font = clamp(state.font + 0.08, 0.84, 1.32);
                if (action === 'font-minus') state.font = clamp(state.font - 0.08, 0.84, 1.32);
                if (action === 'space-plus') state.space = clamp(state.space + 1, 0, 6);
                if (action === 'space-minus') state.space = clamp(state.space - 1, 0, 6);
                if (action === 'line-plus') state.line = clamp(state.line + 0.08, 1, 1.56);
                if (action === 'line-minus') state.line = clamp(state.line - 0.08, 1, 1.56);
                if (['invert', 'grayscale', 'underline', 'cursor', 'guide', 'motion'].indexOf(action) !== -1) {
                    state.flags[action] = ! state.flags[action];
                }

                applyState();
            });
        });

        document.addEventListener('mousemove', function (event) {
            if (! guide || ! state.flags.guide) {
                return;
            }

            guide.style.top = Math.max(0, event.clientY - 20) + 'px';
            guide.classList.remove('hidden');
        });

        applyState();
    });
</script>
