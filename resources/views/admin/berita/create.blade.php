@extends('layouts.admin')
@section('title', ' Tambah Berita')
@section('page_title', 'Tambah Berita')
@section('content')
<div class="card card-primary">
    <div class="card-header">
        <h3 class="card-title">Tambah Berita</h3>
    </div>

    <form action="{{ route('admin.berita.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="card-body">
            @include('admin.berita._form')
        </div>

        <div class="card-footer">
            <a href="{{ route('admin.berita.index') }}" class="btn btn-secondary">Kembali</a>
            <button type="submit" class="btn btn-primary">Simpan</button>
        </div>
    </form>
</div>
@endsection

@include('components.tinymce')