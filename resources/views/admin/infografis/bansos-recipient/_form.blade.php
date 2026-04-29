<style>
    .recipient-form-card {
        border-radius: 14px;
        border: 1px solid #e9ecef;
    }

    .helper-box {
        border-radius: 12px;
        padding: 1rem;
        min-height: 100px;
        color: #fff;
        box-shadow: 0 0.125rem 0.25rem rgba(0,0,0,.06);
    }

    .helper-box .label {
        font-size: .95rem;
        font-weight: 600;
        margin-bottom: .35rem;
    }

    .helper-box .value {
        font-size: 1.15rem;
        font-weight: 700;
        line-height: 1.4;
        word-break: break-word;
    }

    .benefit-badge-preview {
        display: inline-block;
        padding: .45rem .75rem;
        border-radius: 999px;
        font-weight: 600;
        font-size: .9rem;
    }
</style>

@php
    $selectedBenefitType = old('benefit_type', $item->benefit_type ?? 'cash');
@endphp

<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            <label>Program Bansos <span class="text-danger">*</span></label>
            <select name="social_assistance_program_id" required class="form-control @error('social_assistance_program_id') is-invalid @enderror">
                <option value="">-- Pilih Program --</option>
                @foreach($programs as $program)
                    <option value="{{ $program->id }}" {{ (string)old('social_assistance_program_id', $item->social_assistance_program_id ?? '') === (string)$program->id ? 'selected' : '' }}>
                        {{ $program->name }} - {{ $program->year }}
                    </option>
                @endforeach
            </select>
            @error('social_assistance_program_id')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group">
            <label>Dusun</label>
            <select name="hamlet_id" class="form-control @error('hamlet_id') is-invalid @enderror">
                <option value="">-- Pilih Dusun --</option>
                @foreach($hamlets as $hamlet)
                    <option value="{{ $hamlet->id }}" {{ (string)old('hamlet_id', $item->hamlet_id ?? '') === (string)$hamlet->id ? 'selected' : '' }}>
                        {{ $hamlet->name }}
                    </option>
                @endforeach
            </select>
            @error('hamlet_id')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group">
            <label>Nama Penerima <span class="text-danger">*</span></label>
            <input type="text" name="name" required class="form-control @error('name') is-invalid @enderror"
                   value="{{ old('name', $item->name ?? '') }}">
            @error('name')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <div class="col-md-3">
        <div class="form-group">
            <label>NIK <span class="text-danger">*</span></label>
            <input type="text" name="nik" required class="form-control @error('nik') is-invalid @enderror"
                   value="{{ old('nik', $item->nik ?? '') }}">
            @error('nik')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <div class="col-md-3">
        <div class="form-group">
            <label>No. KK</label>
            <input type="text" name="kk_number" class="form-control @error('kk_number') is-invalid @enderror"
                   value="{{ old('kk_number', $item->kk_number ?? '') }}">
            @error('kk_number')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <div class="col-md-8">
        <div class="form-group">
            <label>Alamat</label>
            <textarea name="address" rows="3" class="form-control @error('address') is-invalid @enderror">{{ old('address', $item->address ?? '') }}</textarea>
            @error('address')
                <div class="invalid-feedback d-block">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <div class="col-md-4">
        <div class="form-group">
            <label>No. HP</label>
            <input type="text" name="phone" class="form-control @error('phone') is-invalid @enderror"
                   value="{{ old('phone', $item->phone ?? '') }}">
            @error('phone')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <div class="col-md-4">
        <div class="form-group">
            <label>Tipe Bantuan <span class="text-danger">*</span></label>
            <select name="benefit_type" id="benefit_type" required class="form-control @error('benefit_type') is-invalid @enderror">
                <option value="cash" {{ $selectedBenefitType === 'cash' ? 'selected' : '' }}>Uang Tunai</option>
                <option value="goods" {{ $selectedBenefitType === 'goods' ? 'selected' : '' }}>Barang / Sembako</option>
                <option value="service" {{ $selectedBenefitType === 'service' ? 'selected' : '' }}>Jasa / Layanan</option>
                <option value="mixed" {{ $selectedBenefitType === 'mixed' ? 'selected' : '' }}>Campuran</option>
            </select>
            @error('benefit_type')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <div class="col-md-4" id="amount_field">
        <div class="form-group">
            <label>Nominal Bantuan</label>
            <input type="number" step="0.01" min="0" name="amount" id="amount" class="form-control @error('amount') is-invalid @enderror"
                   value="{{ old('amount', $item->amount ?? 0) }}">
            @error('amount')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
            <small class="text-muted">Isi jika bantuan berupa uang atau campuran.</small>
        </div>
    </div>

    <div class="col-md-4 d-flex align-items-end">
        <div class="mb-3">
            <span id="benefit_type_preview" class="benefit-badge-preview badge badge-success">
                Tunai
            </span>
        </div>
    </div>

    <div class="col-md-8" id="item_description_field">
        <div class="form-group">
            <label>Deskripsi Bantuan</label>
            <textarea name="item_description" id="item_description" rows="3" class="form-control @error('item_description') is-invalid @enderror">{{ old('item_description', $item->item_description ?? '') }}</textarea>
            @error('item_description')
                <div class="invalid-feedback d-block">{{ $message }}</div>
            @enderror
            <small class="text-muted">Contoh: Beras 10 Kg, Paket sembako, Layanan pengobatan gratis.</small>
        </div>
    </div>

    <div class="col-md-2" id="quantity_field">
        <div class="form-group">
            <label>Jumlah</label>
            <input type="number" step="0.01" min="0" name="quantity" id="quantity" class="form-control @error('quantity') is-invalid @enderror"
                   value="{{ old('quantity', $item->quantity ?? '') }}">
            @error('quantity')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <div class="col-md-2" id="unit_field">
        <div class="form-group">
            <label>Satuan</label>
            <input type="text" name="unit" id="unit" class="form-control @error('unit') is-invalid @enderror"
                   value="{{ old('unit', $item->unit ?? '') }}"
                   placeholder="paket / kg / layanan">
            @error('unit')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <div class="col-md-4">
        <div class="form-group">
            <label>Status Verifikasi <span class="text-danger">*</span></label>
            <select name="verification_status" required class="form-control @error('verification_status') is-invalid @enderror">
                <option value="pending" {{ old('verification_status', $item->verification_status ?? 'pending') === 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="verified" {{ old('verification_status', $item->verification_status ?? '') === 'verified' ? 'selected' : '' }}>Terverifikasi</option>
                <option value="rejected" {{ old('verification_status', $item->verification_status ?? '') === 'rejected' ? 'selected' : '' }}>Ditolak</option>
            </select>
            @error('verification_status')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <div class="col-md-4">
        <div class="form-group">
            <label>Status Penyaluran <span class="text-danger">*</span></label>
            <select name="distribution_status" required class="form-control @error('distribution_status') is-invalid @enderror">
                <option value="pending" {{ old('distribution_status', $item->distribution_status ?? 'pending') === 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="ready" {{ old('distribution_status', $item->distribution_status ?? '') === 'ready' ? 'selected' : '' }}>Siap Diambil</option>
                <option value="distributed" {{ old('distribution_status', $item->distribution_status ?? '') === 'distributed' ? 'selected' : '' }}>Sudah Diambil</option>
                <option value="rejected" {{ old('distribution_status', $item->distribution_status ?? '') === 'rejected' ? 'selected' : '' }}>Ditolak</option>
            </select>
            @error('distribution_status')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <div class="col-md-4">
        <div class="form-group">
            <label>Tanggal Diambil</label>
            <input type="date" name="distributed_at" class="form-control @error('distributed_at') is-invalid @enderror"
                   value="{{ old('distributed_at', isset($item) && $item->distributed_at ? $item->distributed_at->format('Y-m-d') : '') }}">
            @error('distributed_at')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group">
            <label>Nama Penerima Saat Pengambilan</label>
            <input type="text" name="receiver_name" class="form-control @error('receiver_name') is-invalid @enderror"
                   value="{{ old('receiver_name', $item->receiver_name ?? '') }}">
            @error('receiver_name')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group">
            <label>Catatan</label>
            <textarea name="notes" rows="3" class="form-control @error('notes') is-invalid @enderror">{{ old('notes', $item->notes ?? '') }}</textarea>
            @error('notes')
                <div class="invalid-feedback d-block">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <div class="col-md-4 mb-3">
        <div class="helper-box bg-info">
            <div class="label">Preview Bantuan</div>
            <div class="value" id="preview_benefit_text">Rp0,00</div>
        </div>
    </div>

    <div class="col-md-4 mb-3">
        <div class="helper-box bg-success">
            <div class="label">Status Saat Ini</div>
            <div class="value" id="preview_status_text">Pending</div>
        </div>
    </div>

    <div class="col-md-4 mb-3">
        <div class="helper-box bg-warning" style="color:#212529">
            <div class="label">Tipe Bantuan</div>
            <div class="value" id="preview_type_text">Tunai</div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function formatRupiah(value) {
        const num = Number(value || 0);
        return 'Rp' + num.toLocaleString('id-ID', {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
        });
    }

    function toggleBenefitFields() {
        const type = document.getElementById('benefit_type')?.value;
        const amountField = document.getElementById('amount_field');
        const itemDescriptionField = document.getElementById('item_description_field');
        const quantityField = document.getElementById('quantity_field');
        const unitField = document.getElementById('unit_field');
        const typePreview = document.getElementById('benefit_type_preview');
        const previewTypeText = document.getElementById('preview_type_text');

        if (!type) return;

        let badgeClass = 'badge badge-success';
        let typeText = 'Tunai';

        if (type === 'cash') {
            amountField.style.display = '';
            itemDescriptionField.style.display = 'none';
            quantityField.style.display = 'none';
            unitField.style.display = 'none';
            badgeClass = 'benefit-badge-preview badge badge-success';
            typeText = 'Tunai';
        } else if (type === 'goods') {
            amountField.style.display = 'none';
            itemDescriptionField.style.display = '';
            quantityField.style.display = '';
            unitField.style.display = '';
            badgeClass = 'benefit-badge-preview badge badge-info';
            typeText = 'Barang';
        } else if (type === 'service') {
            amountField.style.display = 'none';
            itemDescriptionField.style.display = '';
            quantityField.style.display = '';
            unitField.style.display = '';
            badgeClass = 'benefit-badge-preview badge badge-primary';
            typeText = 'Jasa';
        } else {
            amountField.style.display = '';
            itemDescriptionField.style.display = '';
            quantityField.style.display = '';
            unitField.style.display = '';
            badgeClass = 'benefit-badge-preview badge badge-warning';
            typeText = 'Campuran';
        }

        typePreview.className = badgeClass;
        typePreview.textContent = typeText;
        previewTypeText.textContent = typeText;

        updateRecipientPreview();
    }

    function updateRecipientPreview() {
        const type = document.getElementById('benefit_type')?.value;
        const amount = document.getElementById('amount')?.value || 0;
        const itemDescription = document.getElementById('item_description')?.value || '';
        const quantity = document.getElementById('quantity')?.value || '';
        const unit = document.getElementById('unit')?.value || '';
        const distributionStatus = document.querySelector('[name="distribution_status"]')?.value || 'pending';

        const previewBenefitText = document.getElementById('preview_benefit_text');
        const previewStatusText = document.getElementById('preview_status_text');

        let benefitText = formatRupiah(amount);

        if (type === 'goods' || type === 'service') {
            benefitText = itemDescription ? itemDescription : '-';
            if (quantity || unit) {
                benefitText += ' (' + (quantity || '0') + ' ' + (unit || '') + ')';
            }
        }

        if (type === 'mixed') {
            let mixedParts = [];
            if (Number(amount) > 0) mixedParts.push(formatRupiah(amount));
            if (itemDescription) {
                let desc = itemDescription;
                if (quantity || unit) {
                    desc += ' (' + (quantity || '0') + ' ' + (unit || '') + ')';
                }
                mixedParts.push(desc);
            }
            benefitText = mixedParts.length ? mixedParts.join(' + ') : '-';
        }

        let statusText = 'Pending';
        if (distributionStatus === 'ready') statusText = 'Siap Diambil';
        if (distributionStatus === 'distributed') statusText = 'Sudah Diambil';
        if (distributionStatus === 'rejected') statusText = 'Ditolak';

        previewBenefitText.textContent = benefitText;
        previewStatusText.textContent = statusText;
    }

    document.addEventListener('DOMContentLoaded', function () {
        const benefitType = document.getElementById('benefit_type');
        const distributionStatus = document.querySelector('[name="distribution_status"]');
        const amount = document.getElementById('amount');
        const itemDescription = document.getElementById('item_description');
        const quantity = document.getElementById('quantity');
        const unit = document.getElementById('unit');

        if (benefitType) {
            benefitType.addEventListener('change', toggleBenefitFields);
        }

        [distributionStatus, amount, itemDescription, quantity, unit].forEach(function(el) {
            if (el) {
                el.addEventListener('input', updateRecipientPreview);
                el.addEventListener('change', updateRecipientPreview);
            }
        });

        toggleBenefitFields();
        updateRecipientPreview();
    });
</script>
@endpush