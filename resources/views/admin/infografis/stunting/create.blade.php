@extends('layouts.admin')

@section('title', 'Tambah Data Stunting')
@section('page_title', 'Tambah Data Stunting')

@section('content')
@php($item = null)

<div class="card card-primary">
    <div class="card-header">
        <h3 class="card-title">Tambah Data Stunting</h3>
    </div>
    <form action="{{ route('admin.stunting-records.store') }}" method="POST">
        @csrf
        <div class="card-body">
            @include('admin.infografis.stunting._form')
        </div>
        <div class="card-footer">
            <a href="{{ route('admin.stunting-records.index') }}" class="btn btn-secondary">Kembali</a>
            <button class="btn btn-primary" type="submit">Simpan</button>
        </div>
    </form>
</div>
@endsection