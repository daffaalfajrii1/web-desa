@extends('layouts.admin')

@section('title', 'Tambah Pengumuman')
@section('page_title', 'Tambah Pengumuman')

@section('content')
<div class="card card-primary">
    <div class="card-header">
        <h3 class="card-title">Tambah Pengumuman</h3>
    </div>

    <form action="{{ route('admin.pengumuman.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="card-body">
            @include('admin.pengumuman._form')
        </div>

        <div class="card-footer">
            <a href="{{ route('admin.pengumuman.index') }}" class="btn btn-secondary">Kembali</a>
            <button type="submit" class="btn btn-primary">Simpan</button>
        </div>
    </form>
</div>
@endsection

@include('components.tinymce')