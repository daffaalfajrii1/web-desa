<div class="row">
    <div class="col-md-12">
        <div class="form-group">
            <label>Nama Role <span class="text-danger">*</span></label>
            <input
                type="text"
                name="name"
                required
                class="form-control @error('name') is-invalid @enderror"
                value="{{ old('name', $role->name ?? '') }}"
            >
            @error('name')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>
</div>

<div class="form-group">
    <label>Permission</label>
    <div class="row">
        @foreach($permissions as $permission)
            <div class="col-md-4 mb-2">
                <div class="form-check">
                    <input
                        type="checkbox"
                        name="permissions[]"
                        value="{{ $permission->name }}"
                        class="form-check-input"
                        id="permission_{{ md5($permission->name) }}"
                        {{ in_array($permission->name, old('permissions', isset($role) ? $role->permissions->pluck('name')->toArray() : [])) ? 'checked' : '' }}
                    >
                    <label class="form-check-label" for="permission_{{ md5($permission->name) }}">
                        {{ $permission->name }}
                    </label>
                </div>
            </div>
        @endforeach
    </div>
</div>