@php
    $pendapatanVal = old('pendapatan', $item->pendapatan ?? 0);
    $belanjaVal = old('belanja', $item->belanja ?? 0);
    $penerimaanVal = old('pembiayaan_penerimaan', $item->pembiayaan_penerimaan ?? 0);
    $pengeluaranVal = old('pembiayaan_pengeluaran', $item->pembiayaan_pengeluaran ?? 0);
@endphp

<style>
    .calc-box {
        border-radius: 12px;
        padding: 1rem;
        min-height: 100px;
        color: #fff;
        box-shadow: 0 0.125rem 0.25rem rgba(0,0,0,.08);
    }
    .calc-box .title {
        font-size: .95rem;
        font-weight: 600;
        margin-bottom: .45rem;
    }
    .calc-box .value {
        font-size: 1.2rem;
        font-weight: 700;
        word-break: break-word;
    }
</style>

<div class="row">
    <div class="col-md-3">
        <div class="form-group">
            <label>Tahun <span class="text-danger">*</span></label>
            <input type="text" name="year" required class="form-control @error('year') is-invalid @enderror"
                   value="{{ old('year', $item->year ?? date('Y')) }}">
            @error('year')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <div class="col-md-9">
        <div class="form-check mt-4 pt-2">
            <input type="checkbox" name="is_active" value="1" class="form-check-input" id="is_active"
                {{ old('is_active', $item->is_active ?? true) ? 'checked' : '' }}>
            <label class="form-check-label" for="is_active">Aktif ditampilkan</label>
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group">
            <label>Pendapatan <span class="text-danger">*</span></label>
            <input type="number" step="0.01" min="0" name="pendapatan" id="pendapatan" required class="form-control"
                   value="{{ $pendapatanVal }}">
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group">
            <label>Belanja <span class="text-danger">*</span></label>
            <input type="number" step="0.01" min="0" name="belanja" id="belanja" required class="form-control"
                   value="{{ $belanjaVal }}">
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group">
            <label>Pembiayaan Penerimaan <span class="text-danger">*</span></label>
            <input type="number" step="0.01" min="0" name="pembiayaan_penerimaan" id="pembiayaan_penerimaan" required class="form-control"
                   value="{{ $penerimaanVal }}">
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group">
            <label>Pembiayaan Pengeluaran <span class="text-danger">*</span></label>
            <input type="number" step="0.01" min="0" name="pembiayaan_pengeluaran" id="pembiayaan_pengeluaran" required class="form-control"
                   value="{{ $pengeluaranVal }}">
        </div>
    </div>

    <div class="col-md-4 mb-3">
        <div class="calc-box bg-primary">
            <div class="title">Surplus / Defisit</div>
            <div class="value" id="surplus_defisit_preview">Rp0,00</div>
        </div>
    </div>

    <div class="col-md-4 mb-3">
        <div class="calc-box bg-warning" style="color:#212529">
            <div class="title">Pembiayaan Netto</div>
            <div class="value" id="netto_preview">Rp0,00</div>
        </div>
    </div>

    <div class="col-md-4 mb-3">
        <div class="calc-box bg-success">
            <div class="title">SILPA</div>
            <div class="value" id="silpa_preview">Rp0,00</div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function formatRupiah(value) {
        const negative = Number(value) < 0;
        const absVal = Math.abs(Number(value || 0));
        const formatted = 'Rp' + absVal.toLocaleString('id-ID', {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
        });
        return negative ? '-' + formatted : formatted;
    }

    function updateApbdesPreview() {
        const pendapatan = parseFloat(document.getElementById('pendapatan')?.value || 0);
        const belanja = parseFloat(document.getElementById('belanja')?.value || 0);
        const penerimaan = parseFloat(document.getElementById('pembiayaan_penerimaan')?.value || 0);
        const pengeluaran = parseFloat(document.getElementById('pembiayaan_pengeluaran')?.value || 0);

        const surplusDefisit = pendapatan - belanja;
        const netto = penerimaan - pengeluaran;
        const silpa = surplusDefisit + netto;

        const surplusEl = document.getElementById('surplus_defisit_preview');
        const nettoEl = document.getElementById('netto_preview');
        const silpaEl = document.getElementById('silpa_preview');

        surplusEl.textContent = formatRupiah(surplusDefisit);
        nettoEl.textContent = formatRupiah(netto);
        silpaEl.textContent = formatRupiah(silpa);

        surplusEl.style.color = surplusDefisit < 0 ? '#ffdddd' : '#ffffff';
        nettoEl.style.color = netto < 0 ? '#c82333' : '#212529';
        silpaEl.style.color = silpa < 0 ? '#ffdddd' : '#ffffff';
    }

    ['pendapatan', 'belanja', 'pembiayaan_penerimaan', 'pembiayaan_pengeluaran'].forEach(function(id) {
        const el = document.getElementById(id);
        if (el) {
            el.addEventListener('input', updateApbdesPreview);
        }
    });

    document.addEventListener('DOMContentLoaded', updateApbdesPreview);
</script>
@endpush