@extends('layouts.admin')

@section('title', 'Tambah Role')
@section('page_title', 'Tambah Role')

@section('content')
@php($role = null)

<div class="card card-primary">
    <div class="card-header">
        <h3 class="card-title">Tambah Role</h3>
    </div>

    <form action="{{ route('admin.roles.store') }}" method="POST">
        @csrf

        <div class="card-body">
            @include('admin.roles._form')
        </div>

        <div class="card-footer">
            <a href="{{ route('admin.roles.index') }}" class="btn btn-secondary">Kembali</a>
            <button type="submit" class="btn btn-primary">Simpan</button>
        </div>
    </form>
</div>
@endsection