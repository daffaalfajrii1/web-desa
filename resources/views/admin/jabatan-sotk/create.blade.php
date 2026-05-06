@extends('layouts.admin')

@section('title', 'Tambah Jabatan SOTK')
@section('page_title', 'Tambah Jabatan SOTK')

@section('content')
@php($item = null)

<div class="card card-primary">
    <div class="card-header">
        <h3 class="card-title">Tambah Jabatan SOTK</h3>
    </div>

    <form action="{{ route('admin.jabatan-sotk.store') }}" method="POST">
        @csrf
        <div class="card-body">
            @include('admin.jabatan-sotk._form')
        </div>
        <div class="card-footer">
            <a href="{{ route('admin.jabatan-sotk.index') }}" class="btn btn-secondary">Kembali</a>
            <button class="btn btn-primary">Simpan</button>
        </div>
    </form>
</div>
@endsection
