@extends('layouts.admin')

@section('title', 'Tambah Ringkasan SDGS')
@section('page_title', 'Tambah Ringkasan SDGS')

@section('content')
@php($item = null)

<div class="card card-primary">
    <div class="card-header">
        <h3 class="card-title">Tambah Ringkasan SDGS</h3>
    </div>

    <form action="{{ route('admin.sdgs-summaries.store') }}" method="POST">
        @csrf
        <div class="card-body">
            @include('admin.infografis.sdgs.summaries._form')
        </div>
        <div class="card-footer">
            <a href="{{ route('admin.sdgs-summaries.index') }}" class="btn btn-secondary">Kembali</a>
            <button type="submit" class="btn btn-primary">Simpan</button>
        </div>
    </form>
</div>
@endsection