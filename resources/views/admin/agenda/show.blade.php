@extends('layouts.admin')

@section('title', 'Edit Agenda')
@section('page_title', 'Edit Agenda')

@section('content')
<div class="card card-warning">
    <div class="card-header">
        <h3 class="card-title">Edit Agenda</h3>
    </div>

    <form action="{{ route('admin.agenda.update', $item->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="card-body">
            @include('admin.agenda._form')
        </div>

        <div class="card-footer">
            <a href="{{ route('admin.agenda.index') }}" class="btn btn-secondary">Kembali</a>
            <button type="submit" class="btn btn-warning">Update</button>
        </div>
    </form>
</div>
@endsection

@include('components.tinymce')