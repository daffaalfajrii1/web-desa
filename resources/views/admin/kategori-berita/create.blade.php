@extends('layouts.admin')
@section('title', ' Tambah Kategori Berita')
@section('page_title', ' Tambah Kategori Berita')
@section('content')
<div class="card card-primary">
    <div class="card-header">
        <h3 class="card-title">Tambah Kategori Berita</h3>
    </div>

    <form action="{{ route('admin.kategori-berita.store') }}" method="POST">
        @csrf

        <div class="card-body">
            @include('admin.kategori-berita._form')
        </div>

        <div class="card-footer">
            <a href="{{ route('admin.kategori-berita.index') }}" class="btn btn-secondary">Kembali</a>
            <button type="submit" class="btn btn-primary">Simpan</button>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const nameInput = document.querySelector('input[name="name"]');
    const slugInput = document.querySelector('input[name="slug"]');

    if (nameInput && slugInput) {
        nameInput.addEventListener('input', function () {
            if (!slugInput.dataset.manual) {
                slugInput.value = nameInput.value
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