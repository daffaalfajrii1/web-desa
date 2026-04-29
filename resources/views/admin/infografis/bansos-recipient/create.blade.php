@extends('layouts.admin')

@section('title', 'Tambah Penerima Bansos')
@section('page_title', 'Tambah Penerima Bansos')

@section('content')
@php($item = null)

<div class="card card-primary recipient-form-card">
    <div class="card-header">
        <h3 class="card-title">Tambah Penerima Bansos</h3>
    </div>

    <form action="{{ route('admin.bansos-recipient.store') }}" method="POST">
        @csrf
        <div class="card-body">
            @include('admin.infografis.bansos-recipient._form')
        </div>
        <div class="card-footer">
            <a href="{{ route('admin.bansos-recipient.index') }}" class="btn btn-secondary">Kembali</a>
            <button class="btn btn-primary" type="submit">Simpan</button>
        </div>
    </form>
</div>
@endsection