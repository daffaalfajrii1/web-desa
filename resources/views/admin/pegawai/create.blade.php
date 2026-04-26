@extends('layouts.admin')

@section('title', 'Tambah Pegawai')
@section('page_title', 'Tambah Pegawai')

@section('content')
@php($item = null)

<div class="card card-primary">
    <div class="card-header">
        <h3 class="card-title">Tambah Pegawai / SOTK</h3>
    </div>

    <form action="{{ route('admin.pegawai.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="card-body">
            @include('admin.pegawai._form')
        </div>

        <div class="card-footer">
            <a href="{{ route('admin.pegawai.index') }}" class="btn btn-secondary">Kembali</a>
            <button type="submit" class="btn btn-primary">Simpan</button>
        </div>
    </form>
</div>
@endsection