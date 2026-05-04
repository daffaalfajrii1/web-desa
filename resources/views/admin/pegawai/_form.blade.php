<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            <label>Hubungkan ke Akun User</label>
            <select name="user_id" class="form-control @error('user_id') is-invalid @enderror">
                <option value="">-- Tidak dihubungkan --</option>
                @foreach($users as $user)
                    <option value="{{ $user->id }}"
                        {{ (string) old('user_id', $item->user_id ?? '') === (string) $user->id ? 'selected' : '' }}>
                        {{ $user->name }} ({{ $user->email }})
                    </option>
                @endforeach
            </select>
            <small class="text-muted">Opsional. Pilih jika pegawai ini punya akun login.</small>
            @error('user_id')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group">
            <label>Nama Pegawai <span class="text-danger">*</span></label>
            <input type="text" name="name" required class="form-control @error('name') is-invalid @enderror"
                   value="{{ old('name', $item->name ?? '') }}">
            @error('name')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group">
            <label>Jabatan <span class="text-danger">*</span></label>
            <input type="text" name="position" required class="form-control @error('position') is-invalid @enderror"
                   value="{{ old('position', $item->position ?? '') }}"
                   placeholder="Contoh: Sekretaris Desa">
            @error('position')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group">
            <label>Jenis Jabatan</label>
            <input type="text" name="position_type" class="form-control @error('position_type') is-invalid @enderror"
                   value="{{ old('position_type', $item->position_type ?? '') }}"
                   placeholder="Contoh: kepala_desa / kasi / kaur / staf">
            @error('position_type')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <div class="col-md-4">
        <div class="form-group">
            <label>NIP / NIK</label>
            <input type="text" name="nip" class="form-control @error('nip') is-invalid @enderror"
                   value="{{ old('nip', $item->nip ?? '') }}">
            @error('nip')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <div class="col-md-4">
        <div class="form-group">
            <label>Email</label>
            <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                   value="{{ old('email', $item->email ?? '') }}">
            @error('email')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <div class="col-md-4">
        <div class="form-group">
            <label>Telepon</label>
            <input type="text" name="phone" class="form-control @error('phone') is-invalid @enderror"
                   value="{{ old('phone', $item->phone ?? '') }}">
            @error('phone')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <div class="col-md-4">
        <div class="form-group">
            <label>Facebook</label>
            <input type="text" name="facebook" class="form-control" value="{{ old('facebook', $item->facebook ?? '') }}">
        </div>
    </div>

    <div class="col-md-4">
        <div class="form-group">
            <label>Instagram</label>
            <input type="text" name="instagram" class="form-control" value="{{ old('instagram', $item->instagram ?? '') }}">
        </div>
    </div>

    <div class="col-md-4">
        <div class="form-group">
            <label>Twitter/X</label>
            <input type="text" name="twitter" class="form-control" value="{{ old('twitter', $item->twitter ?? '') }}">
        </div>
    </div>

    <div class="col-md-4">
        <div class="form-group">
            <label>YouTube</label>
            <input type="text" name="youtube" class="form-control" value="{{ old('youtube', $item->youtube ?? '') }}">
        </div>
    </div>

    <div class="col-md-4">
        <div class="form-group">
            <label>WhatsApp</label>
            <input type="text" name="whatsapp" class="form-control" value="{{ old('whatsapp', $item->whatsapp ?? '') }}">
        </div>
    </div>

    <div class="col-md-4">
        <div class="form-group">
            <label>Telegram</label>
            <input type="text" name="telegram" class="form-control" value="{{ old('telegram', $item->telegram ?? '') }}">
        </div>
    </div>

    <div class="col-md-4">
        <div class="form-group">
            <label>Urutan Tampil</label>
            <input type="number" name="sort_order" class="form-control @error('sort_order') is-invalid @enderror"
                   value="{{ old('sort_order', $item->sort_order ?? 0) }}">
            @error('sort_order')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <div class="col-md-4">
        <div class="form-group">
            <label>PIN Absensi <span class="text-danger">*</span></label>
            <input type="text" name="pin_absensi" class="form-control @error('pin_absensi') is-invalid @enderror"
                   value="{{ old('pin_absensi', $item->pin_absensi ?? $item->attendance_pin ?? '') }}"
                   required minlength="4" maxlength="20">
            @error('pin_absensi')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
            <small class="text-muted">Wajib diisi agar pegawai bisa digunakan untuk absensi.</small>
        </div>
    </div>

    <div class="col-md-4">
        <div class="form-group">
            <label>Foto</label>
            <input type="file" name="photo" class="form-control-file @error('photo') is-invalid @enderror">
            @error('photo')
                <div class="text-danger small">{{ $message }}</div>
            @enderror

            @if(!empty($item?->photo))
                <div class="mt-2">
                    <img src="{{ asset('storage/' . $item->photo) }}" alt="foto pegawai" width="140">
                </div>
            @endif
        </div>
    </div>

    <div class="col-md-12">
        <div class="form-check">
            <input type="checkbox" name="is_active" value="1" class="form-check-input" id="is_active"
                {{ old('is_active', $item->is_active ?? true) ? 'checked' : '' }}>
            <label class="form-check-label" for="is_active">Pegawai aktif</label>
        </div>
    </div>
</div>
