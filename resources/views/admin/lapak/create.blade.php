@extends('layouts.admin')

@section('title', 'Tambah Produk Lapak')
@section('page_title', 'Tambah Produk Lapak')

@section('content')
@php($item = null)

<div class="card card-primary">
    <div class="card-header">
        <h3 class="card-title">Tambah Produk Lapak</h3>
    </div>

    <form action="{{ route('admin.lapak.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="card-body">
            @include('admin.lapak._form')
        </div>
        <div class="card-footer">
            <a href="{{ route('admin.lapak.index') }}" class="btn btn-secondary">Kembali</a>
            <button class="btn btn-primary">Simpan</button>
        </div>
    </form>
</div>
@endsection

@include('components.tinymce')