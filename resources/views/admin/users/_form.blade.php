<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            <label>Foto Profil</label>
            <div class="d-flex align-items-start" style="gap: 12px;">
                <img
                    src="{{ isset($user) ? $user->admin_photo_url : 'https://adminlte.io/themes/v3/dist/img/user2-160x160.jpg' }}"
                    alt="Foto user"
                    class="img-circle elevation-2"
                    style="width:68px;height:68px;object-fit:cover;"
                >
                <div class="flex-fill">
                    <input
                        type="file"
                        name="photo"
                        accept="image/jpeg,image/png,image/webp"
                        class="form-control-file @error('photo') is-invalid @enderror"
                    >
                    <small class="form-text text-muted">JPG/PNG/WEBP, maksimal 4 MB.</small>
                    @if(isset($user) && $user->photo_path)
                        <div class="form-check mt-2">
                            <input type="checkbox" class="form-check-input" id="remove_photo" name="remove_photo" value="1">
                            <label class="form-check-label text-danger" for="remove_photo">Hapus foto saat ini</label>
                        </div>
                    @endif
                    @error('photo')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-6"></div>

    <div class="col-md-6">
        <div class="form-group">
            <label>Nama <span class="text-danger">*</span></label>
            <input
                type="text"
                name="name"
                required
                class="form-control @error('name') is-invalid @enderror"
                value="{{ old('name', $user->name ?? '') }}"
            >
            @error('name')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group">
            <label>Email <span class="text-danger">*</span></label>
            <input
                type="email"
                name="email"
                required
                class="form-control @error('email') is-invalid @enderror"
                value="{{ old('email', $user->email ?? '') }}"
            >
            @error('email')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group">
            <label>Role <span class="text-danger">*</span></label>
            <select name="role" required class="form-control @error('role') is-invalid @enderror">
                <option value="">-- Pilih Role --</option>
                @foreach($roles as $role)
                    <option value="{{ $role->name }}"
                        {{ old('role', isset($user) ? $user->roles->first()?->name : '') === $role->name ? 'selected' : '' }}>
                        {{ $role->name }}
                    </option>
                @endforeach
            </select>
            @error('role')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>
</div>