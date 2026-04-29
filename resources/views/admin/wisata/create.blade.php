@extends('layouts.admin')

@section('title', 'Tambah Wisata')
@section('page_title', 'Tambah Wisata')

@section('content')
@php($item = null)

<div class="card card-primary">
    <div class="card-header">
        <h3 class="card-title">Tambah Wisata</h3>
    </div>

    <form action="{{ route('admin.wisata.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="card-body">
            @include('admin.wisata._form')
        </div>
        <div class="card-footer">
            <a href="{{ route('admin.wisata.index') }}" class="btn btn-secondary">Kembali</a>
            <button class="btn btn-primary" type="submit">Simpan</button>
        </div>
    </form>
</div>
@endsection

@include('components.tinymce')