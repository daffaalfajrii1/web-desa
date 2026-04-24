@extends('layouts.admin')

@section('title', 'Tambah Agenda')
@section('page_title', 'Tambah Agenda')

@section('content')
<div class="card card-primary">
    <div class="card-header">
        <h3 class="card-title">Tambah Agenda</h3>
    </div>

    <form action="{{ route('admin.agenda.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="card-body">
            @include('admin.agenda._form')
        </div>

        <div class="card-footer">
            <a href="{{ route('admin.agenda.index') }}" class="btn btn-secondary">Kembali</a>
            <button type="submit" class="btn btn-primary">Simpan</button>
        </div>
    </form>
</div>
@endsection

@include('components.tinymce')