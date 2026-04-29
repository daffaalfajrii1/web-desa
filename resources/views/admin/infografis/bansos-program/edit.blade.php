@extends('layouts.admin')

@section('title', 'Edit Program Bansos')
@section('page_title', 'Edit Program Bansos')

@section('content')
<div class="card card-warning">
    <div class="card-header">
        <h3 class="card-title">Edit Program Bansos</h3>
    </div>

    <form action="{{ route('admin.bansos-program.update', $item->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="card-body">
            @include('admin.infografis.bansos-program._form')
        </div>
        <div class="card-footer">
            <a href="{{ route('admin.bansos-program.index') }}" class="btn btn-secondary">Kembali</a>
            <button class="btn btn-warning" type="submit">Update</button>
        </div>
    </form>
</div>
@endsection