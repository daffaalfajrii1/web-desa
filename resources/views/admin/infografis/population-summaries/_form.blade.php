<div class="row">
    <div class="col-md-4">
        <div class="form-group">
            <label>Tahun <span class="text-danger">*</span></label>
            <input type="text" name="year" required class="form-control @error('year') is-invalid @enderror"
                   value="{{ old('year', $item->year ?? date('Y')) }}">
            @error('year')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <div class="col-md-8">
        <div class="form-group">
            <label>Dusun <span class="text-danger">*</span></label>
            <select name="hamlet_id" required class="form-control @error('hamlet_id') is-invalid @enderror">
                <option value="">-- Pilih Dusun --</option>
                @foreach($hamlets as $hamlet)
                    <option value="{{ $hamlet->id }}"
                        {{ (string) old('hamlet_id', $item->hamlet_id ?? '') === (string) $hamlet->id ? 'selected' : '' }}>
                        {{ $hamlet->name }}
                    </option>
                @endforeach
            </select>
            @error('hamlet_id')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <div class="col-md-4">
        <div class="form-group">
            <label>Jumlah KK <span class="text-danger">*</span></label>
            <input type="number" min="0" name="total_kk" required class="form-control"
                   value="{{ old('total_kk', $item->total_kk ?? 0) }}">
        </div>
    </div>

    <div class="col-md-4">
        <div class="form-group">
            <label>Laki-laki <span class="text-danger">*</span></label>
            <input type="number" min="0" name="male_count" required class="form-control"
                   value="{{ old('male_count', $item->male_count ?? 0) }}">
        </div>
    </div>

    <div class="col-md-4">
        <div class="form-group">
            <label>Perempuan <span class="text-danger">*</span></label>
            <input type="number" min="0" name="female_count" required class="form-control"
                   value="{{ old('female_count', $item->female_count ?? 0) }}">
        </div>
    </div>
</div>