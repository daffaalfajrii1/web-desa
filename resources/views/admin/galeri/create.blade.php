@extends('layouts.admin')

@section('title', 'Tambah Galeri')
@section('page_title', 'Tambah Galeri')

@section('content')
@php($item = null)

<div class="card card-primary">
    <div class="card-header">
        <h3 class="card-title">Tambah Galeri</h3>
    </div>

    <form action="{{ route('admin.galeri.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="card-body">
            @include('admin.galeri._form')
        </div>
        <div class="card-footer">
            <a href="{{ route('admin.galeri.index') }}" class="btn btn-secondary">Kembali</a>
            <button class="btn btn-primary" type="submit">Simpan</button>
        </div>
    </form>
</div>
@endsection
