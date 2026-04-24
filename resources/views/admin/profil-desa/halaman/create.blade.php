@extends('layouts.admin')
@section('title', ' Tambah HalamanProfil Desa')
@section('page_title', ' Tambah Halaman Profil Desa')
@section('content')
<div class="card card-primary">
    <div class="card-header">
        <h3 class="card-title">Tambah Halaman Profil</h3>
    </div>

    <form action="{{ route('admin.profil-desa.halaman.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="card-body">
            @include('admin.profil-desa.halaman._form')
        </div>

        <div class="card-footer">
            <a href="{{ route('admin.profil-desa.halaman.index') }}" class="btn btn-secondary">Kembali</a>
            <button type="submit" class="btn btn-primary">Simpan</button>
        </div>
    </form>
</div>
@endsection

@include('components.tinymce')