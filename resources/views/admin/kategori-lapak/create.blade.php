@extends('layouts.admin')

@section('title', 'Tambah Kategori Lapak')
@section('page_title', 'Tambah Kategori Lapak')

@section('content')
@php($item = null)

<div class="card card-primary">
    <div class="card-header">
        <h3 class="card-title">Tambah Kategori Lapak</h3>
    </div>

    <form action="{{ route('admin.kategori-lapak.store') }}" method="POST">
        @csrf
        <div class="card-body">
            @include('admin.kategori-lapak._form')
        </div>
        <div class="card-footer">
            <a href="{{ route('admin.kategori-lapak.index') }}" class="btn btn-secondary">Kembali</a>
            <button class="btn btn-primary">Simpan</button>
        </div>
    </form>
</div>
@endsection