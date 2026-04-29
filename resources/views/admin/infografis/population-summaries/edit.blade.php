@extends('layouts.admin')

@section('title', 'Edit Ringkasan Penduduk')
@section('page_title', 'Edit Ringkasan Penduduk')

@section('content')
<div class="card card-warning">
    <div class="card-header">
        <h3 class="card-title">Edit Ringkasan Penduduk</h3>
    </div>

    <form action="{{ route('admin.population-summaries.update', $item->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="card-body">
            @include('admin.infografis.population-summaries._form')
        </div>
        <div class="card-footer">
            <a href="{{ route('admin.population-summaries.index') }}" class="btn btn-secondary">Kembali</a>
            <button class="btn btn-warning" type="submit">Update</button>
        </div>
    </form>
</div>
@endsection