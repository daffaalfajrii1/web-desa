@extends('layouts.admin')

@section('title', 'Tambah Dusun')
@section('page_title', 'Tambah Dusun')

@section('content')
@php($hamlet = null)

<div class="card card-primary">
    <div class="card-header">
        <h3 class="card-title">Tambah Dusun</h3>
    </div>

    <form action="{{ route('admin.hamlets.store') }}" method="POST">
        @csrf
        <div class="card-body">
            @include('admin.infografis.hamlets._form')
        </div>
        <div class="card-footer">
            <a href="{{ route('admin.hamlets.index') }}" class="btn btn-secondary">Kembali</a>
            <button class="btn btn-primary" type="submit">Simpan</button>
        </div>
    </form>
</div>
@endsection