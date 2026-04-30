@extends('layouts.admin')

@section('title', 'Tambah Nilai Tujuan SDGS')
@section('page_title', 'Tambah Nilai Tujuan SDGS')

@section('content')
@php($item = null)

<div class="card card-primary">
    <div class="card-header">
        <h3 class="card-title">Tambah Nilai Tujuan SDGS</h3>
    </div>

    <form action="{{ route('admin.sdgs-goal-values.store') }}" method="POST">
        @csrf
        <div class="card-body">
            @include('admin.infografis.sdgs.goal-values._form')
        </div>
        <div class="card-footer">
            <a href="{{ route('admin.sdgs-goal-values.index') }}" class="btn btn-secondary">Kembali</a>
            <button type="submit" class="btn btn-primary">Simpan</button>
        </div>
    </form>
</div>
@endsection