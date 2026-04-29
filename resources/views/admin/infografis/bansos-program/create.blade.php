@extends('layouts.admin')

@section('title', 'Tambah Program Bansos')
@section('page_title', 'Tambah Program Bansos')

@section('content')
@php($item = null)

<div class="card card-primary">
    <div class="card-header">
        <h3 class="card-title">Tambah Program Bansos</h3>
    </div>

    <form action="{{ route('admin.bansos-program.store') }}" method="POST">
        @csrf
        <div class="card-body">
            @include('admin.infografis.bansos-program._form')
        </div>
        <div class="card-footer">
            <a href="{{ route('admin.bansos-program.index') }}" class="btn btn-secondary">Kembali</a>
            <button class="btn btn-primary" type="submit">Simpan</button>
        </div>
    </form>
</div>
@endsection