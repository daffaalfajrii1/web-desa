@extends('layouts.admin')
@section('title', ' Tambah Menu Profil Desa')
@section('page_title', ' Tambah Menu Profil Desa')
@section('content')
<div class="card card-primary">
    <div class="card-header">
        <h3 class="card-title">Tambah Menu Profil</h3>
    </div>

    <form action="{{ route('admin.profil-desa.menu.store') }}" method="POST">
        @csrf

        <div class="card-body">
            @include('admin.profil-desa.menu._form')
        </div>

        <div class="card-footer">
            <a href="{{ route('admin.profil-desa.menu.index') }}" class="btn btn-secondary">Kembali</a>
            <button type="submit" class="btn btn-primary">Simpan</button>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const titleInput = document.querySelector('input[name="title"]');
    const slugInput = document.querySelector('input[name="slug"]');

    if (titleInput && slugInput) {
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