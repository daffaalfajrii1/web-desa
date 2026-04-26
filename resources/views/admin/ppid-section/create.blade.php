@extends('layouts.admin')
@section('title', 'Tambah Section PPID')
@section('page_title', 'Tambah Section PPID')

@section('content')
<div class="card card-primary">
    <div class="card-header"><h3 class="card-title">Tambah Section PPID</h3></div>
    <form action="{{ route('admin.ppid-section.store') }}" method="POST">
        @csrf
        <div class="card-body">
            @include('admin.ppid-section._form')
        </div>
        <div class="card-footer">
            <a href="{{ route('admin.ppid-section.index') }}" class="btn btn-secondary">Kembali</a>
            <button type="submit" class="btn btn-primary">Simpan</button>
        </div>
    </form>
</div>
@endsection