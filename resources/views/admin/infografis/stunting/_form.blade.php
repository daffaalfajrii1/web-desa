<div class="row">
    <div class="col-md-3">
        <div class="form-group">
            <label>Tahun <span class="text-danger">*</span></label>
            <input type="text" name="year" required class="form-control" value="{{ old('year', $item->year ?? date('Y')) }}">
        </div>
    </div>

    <div class="col-md-3">
        <div class="form-group">
            <label>Dusun</label>
            <select name="hamlet_id" class="form-control">
                <option value="">-- Pilih Dusun --</option>
                @foreach($hamlets as $hamlet)
                    <option value="{{ $hamlet->id }}" {{ (string)old('hamlet_id', $item->hamlet_id ?? '') === (string)$hamlet->id ? 'selected' : '' }}>
                        {{ $hamlet->name }}
                    </option>
                @endforeach
            </select>
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group">
            <label>Nama Anak <span class="text-danger">*</span></label>
            <input type="text" name="child_name" required class="form-control" value="{{ old('child_name', $item->child_name ?? '') }}">
        </div>
    </div>

    <div class="col-md-4">
        <div class="form-group">
            <label>NIK Anak</label>
            <input type="text" name="child_nik" class="form-control" value="{{ old('child_nik', $item->child_nik ?? '') }}">
        </div>
    </div>

    <div class="col-md-4">
        <div class="form-group">
            <label>Nama Orang Tua</label>
            <input type="text" name="parent_name" class="form-control" value="{{ old('parent_name', $item->parent_name ?? '') }}">
        </div>
    </div>

    <div class="col-md-4">
        <div class="form-group">
            <label>Jenis Kelamin <span class="text-danger">*</span></label>
            <select name="gender" class="form-control" required>
                <option value="L" {{ old('gender', $item->gender ?? '') === 'L' ? 'selected' : '' }}>Laki-laki</option>
                <option value="P" {{ old('gender', $item->gender ?? '') === 'P' ? 'selected' : '' }}>Perempuan</option>
            </select>
        </div>
    </div>

    <div class="col-md-3">
        <div class="form-group">
            <label>Tanggal Lahir</label>
            <input type="date" name="birth_date" class="form-control" value="{{ old('birth_date', isset($item) && $item->birth_date ? $item->birth_date->format('Y-m-d') : '') }}">
        </div>
    </div>

    <div class="col-md-3">
        <div class="form-group">
            <label>Usia (bulan)</label>
            <input type="number" name="age_in_months" min="0" class="form-control" value="{{ old('age_in_months', $item->age_in_months ?? '') }}">
        </div>
    </div>

    <div class="col-md-3">
        <div class="form-group">
            <label>Tinggi Badan (cm)</label>
            <input type="number" step="0.01" min="0" name="height_cm" class="form-control" value="{{ old('height_cm', $item->height_cm ?? '') }}">
        </div>
    </div>

    <div class="col-md-3">
        <div class="form-group">
            <label>Berat Badan (kg)</label>
            <input type="number" step="0.01" min="0" name="weight_kg" class="form-control" value="{{ old('weight_kg', $item->weight_kg ?? '') }}">
        </div>
    </div>

    <div class="col-md-4">
        <div class="form-group">
            <label>Status Stunting <span class="text-danger">*</span></label>
            <select name="stunting_status" class="form-control" required>
                <option value="normal" {{ old('stunting_status', $item->stunting_status ?? 'normal') === 'normal' ? 'selected' : '' }}>Normal</option>
                <option value="stunting" {{ old('stunting_status', $item->stunting_status ?? '') === 'stunting' ? 'selected' : '' }}>Stunting</option>
                <option value="berisiko" {{ old('stunting_status', $item->stunting_status ?? '') === 'berisiko' ? 'selected' : '' }}>Berisiko</option>
            </select>
        </div>
    </div>

    <div class="col-md-4">
        <div class="form-group">
            <label>Status Gizi <span class="text-danger">*</span></label>
            <select name="nutrition_status" class="form-control" required>
                <option value="baik" {{ old('nutrition_status', $item->nutrition_status ?? 'baik') === 'baik' ? 'selected' : '' }}>Baik</option>
                <option value="kurang" {{ old('nutrition_status', $item->nutrition_status ?? '') === 'kurang' ? 'selected' : '' }}>Kurang</option>
                <option value="buruk" {{ old('nutrition_status', $item->nutrition_status ?? '') === 'buruk' ? 'selected' : '' }}>Buruk</option>
            </select>
        </div>
    </div>

    <div class="col-md-4 d-flex align-items-center">
        <div class="form-check mt-3">
            <input type="checkbox" name="is_active" value="1" class="form-check-input" id="is_active"
                {{ old('is_active', $item->is_active ?? true) ? 'checked' : '' }}>
            <label class="form-check-label" for="is_active">Data aktif</label>
        </div>
    </div>

    <div class="col-md-12">
        <div class="form-group">
            <label>Keterangan</label>
            <textarea name="notes" rows="3" class="form-control">{{ old('notes', $item->notes ?? '') }}</textarea>
        </div>
    </div>
</div>