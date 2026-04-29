@extends('layouts.admin')

@section('title', 'Tambah APBDes')
@section('page_title', 'Tambah APBDes')

@section('content')
@php($item = null)

<div class="card card-primary">
    <div class="card-header">
        <h3 class="card-title">Tambah Data APBDes</h3>
    </div>

    <form action="{{ route('admin.apbdes.store') }}" method="POST">
        @csrf
        <div class="card-body">
            @include('admin.infografis.apbdes._form')
        </div>
        <div class="card-footer">
            <a href="{{ route('admin.apbdes.index') }}" class="btn btn-secondary">Kembali</a>
            <button class="btn btn-primary" type="submit">Simpan</button>
        </div>
    </form>
</div>
@endsection