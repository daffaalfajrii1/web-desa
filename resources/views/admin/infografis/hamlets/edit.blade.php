@extends('layouts.admin')

@section('title', 'Edit Dusun')
@section('page_title', 'Edit Dusun')

@section('content')
<div class="card card-warning">
    <div class="card-header">
        <h3 class="card-title">Edit Dusun</h3>
    </div>

    <form action="{{ route('admin.hamlets.update', $hamlet->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="card-body">
            @include('admin.infografis.hamlets._form')
        </div>
        <div class="card-footer">
            <a href="{{ route('admin.hamlets.index') }}" class="btn btn-secondary">Kembali</a>
            <button class="btn btn-warning" type="submit">Update</button>
        </div>
    </form>
</div>
@endsection