@extends('layouts.admin')

@section('content')
<div class="card card-warning">
    <div class="card-header">
        <h3 class="card-title">Edit Menu Profil</h3>
    </div>

    <form action="{{ route('admin.profil-desa.menu.update', $menu->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="card-body">
            @include('admin.profil-desa.menu._form')
        </div>

        <div class="card-footer">
            <a href="{{ route('admin.profil-desa.menu.index') }}" class="btn btn-secondary">Kembali</a>
            <button type="submit" class="btn btn-warning">Update</button>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const titleInput = document.querySelector('input[name="title"]');
    const slugInput = document.querySelector('input[name="slug"]');

    if (titleInput && slugInput && !slugInput.value) {
        titleInput.addEventListener('input', function () {
            if (!slugInput.dataset.manual) {
                slugInput.value = titleInput.value
                    .toLowerCase()
                    .trim()
                    .replace(/[^a-z0-9\s-]/g, '')
                    .replace(/\s+/g, '-')
                    .replace(/-+/g, '-');
            }
        });

        slugInput.addEventListener('input', function () {
            slugInput.dataset.manual = 'true';
        });
    }
});
</script>
@endpush