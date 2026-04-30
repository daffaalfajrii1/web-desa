@extends('layouts.admin')

@section('title', 'Edit Data Stunting')
@section('page_title', 'Edit Data Stunting')

@section('content')
<div class="card card-warning">
    <div class="card-header">
        <h3 class="card-title">Edit Data Stunting</h3>
    </div>
    <form action="{{ route('admin.stunting-records.update', $item->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="card-body">
            @include('admin.infografis.stunting._form')
        </div>
        <div class="card-footer">
            <a href="{{ route('admin.stunting-records.index') }}" class="btn btn-secondary">Kembali</a>
            <button class="btn btn-warning" type="submit">Update</button>
        </div>
    </form>
</div>
@endsection