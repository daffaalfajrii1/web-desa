@extends('layouts.admin')
@section('title', ' Edit Halaman Profil Desa')
@section('page_title', 'Edit Halaman Profil Desa')
@section('content')
<div class="card card-warning">
    <div class="card-header">
        <h3 class="card-title">Edit Halaman Profil</h3>
    </div>

    <form action="{{ route('admin.profil-desa.halaman.update', $page->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="card-body">
            @include('admin.profil-desa.halaman._form')
        </div>

        <div class="card-footer">
            <a href="{{ route('admin.profil-desa.halaman.index') }}" class="btn btn-secondary">Kembali</a>
            <button type="submit" class="btn btn-warning">Update</button>
        </div>
    </form>
</div>
@endsection

@include('components.tinymce')