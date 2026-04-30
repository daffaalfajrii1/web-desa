<div class="row">
    <div class="col-md-4">
        <div class="form-group">
            <label>Tahun SDGS <span class="text-danger">*</span></label>
            <select name="sdgs_summary_id" class="form-control @error('sdgs_summary_id') is-invalid @enderror" required>
                <option value="">-- Pilih Tahun --</option>
                @foreach($summaries as $summary)
                    <option value="{{ $summary->id }}" {{ (string)old('sdgs_summary_id', $item->sdgs_summary_id ?? request('sdgs_summary_id')) === (string)$summary->id ? 'selected' : '' }}>
                        {{ $summary->year }}
                    </option>
                @endforeach
            </select>
            @error('sdgs_summary_id')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <div class="col-md-8">
        <div class="form-group">
            <label>Tujuan SDGS <span class="text-danger">*</span></label>
            <select name="sdgs_goal_id" class="form-control @error('sdgs_goal_id') is-invalid @enderror" required>
                <option value="">-- Pilih Tujuan --</option>
                @foreach($goals as $goal)
                    <option value="{{ $goal->id }}" {{ (string)old('sdgs_goal_id', $item->sdgs_goal_id ?? '') === (string)$goal->id ? 'selected' : '' }}>
                        {{ $goal->goal_number }}. {{ $goal->goal_name }}
                    </option>
                @endforeach
            </select>
            @error('sdgs_goal_id')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <div class="col-md-4">
        <div class="form-group">
            <label>Skor <span class="text-danger">*</span></label>
            <input type="number" step="0.01" min="0" max="100" name="score" id="score" class="form-control @error('score') is-invalid @enderror"
                   value="{{ old('score', $item->score ?? 0) }}" required>
            @error('score')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <div class="col-md-4">
        <div class="form-group">
            <label>Capaian (%)</label>
            <input type="number" step="0.01" min="0" max="100" name="achievement_percent" class="form-control @error('achievement_percent') is-invalid @enderror"
                   value="{{ old('achievement_percent', $item->achievement_percent ?? '') }}">
            @error('achievement_percent')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <div class="col-md-4 d-flex align-items-end">
        <div class="mb-3">
            <span id="status_preview" class="badge badge-danger p-2">Prioritas</span>
        </div>
    </div>

    <div class="col-md-12">
        <div class="form-group">
            <label>Deskripsi Singkat</label>
            <textarea name="short_description" rows="4" class="form-control @error('short_description') is-invalid @enderror">{{ old('short_description', $item->short_description ?? '') }}</textarea>
            @error('short_description')
                <div class="invalid-feedback d-block">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <div class="col-md-3">
        <div class="form-group">
            <label>Urutan</label>
            <input type="number" min="0" name="sort_order" class="form-control"
                   value="{{ old('sort_order', $item->sort_order ?? 0) }}">
        </div>
    </div>

    <div class="col-md-12">
        <div class="form-check">
            <input type="checkbox" name="is_active" value="1" class="form-check-input" id="is_active"
                {{ old('is_active', $item->is_active ?? true) ? 'checked' : '' }}>
            <label class="form-check-label" for="is_active">Data aktif</label>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function updateSdgsStatusPreview() {
        const score = parseFloat(document.getElementById('score')?.value || 0);
        const preview = document.getElementById('status_preview');
        if (!preview) return;

        if (score >= 80) {
            preview.className = 'badge badge-success p-2';
            preview.textContent = 'Baik';
        } else if (score >= 60) {
            preview.className = 'badge badge-warning p-2';
            preview.textContent = 'Berkembang';
        } else {
            preview.className = 'badge badge-danger p-2';
            preview.textContent = 'Prioritas';
        }
    }

    document.addEventListener('DOMContentLoaded', function () {
        const score = document.getElementById('score');
        if (score) {
            score.addEventListener('input', updateSdgsStatusPreview);
            updateSdgsStatusPreview();
        }
    });
</script>
@endpush