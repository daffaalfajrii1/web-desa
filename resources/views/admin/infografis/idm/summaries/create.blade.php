@extends('layouts.admin')

@section('title', 'Tambah Ringkasan IDM')
@section('page_title', 'Tambah Ringkasan IDM')

@section('content')
@php($item = null)

<div class="card card-primary">
    <div class="card-header">
        <h3 class="card-title">Tambah Ringkasan IDM</h3>
    </div>
    <form action="{{ route('admin.idm-summaries.store') }}" method="POST">
        @csrf
        <div class="card-body">
            @include('admin.infografis.idm.summaries._form')
        </div>
        <div class="card-footer">
            <a href="{{ route('admin.idm-summaries.index') }}" class="btn btn-secondary">Kembali</a>
            <button type="submit" class="btn btn-primary">Simpan</button>
        </div>
    </form>
</div>
@endsection