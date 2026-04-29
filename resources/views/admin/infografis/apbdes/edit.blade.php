@extends('layouts.admin')

@section('title', 'Edit APBDes')
@section('page_title', 'Edit APBDes')

@section('content')
<div class="card card-warning">
    <div class="card-header">
        <h3 class="card-title">Edit Data APBDes</h3>
    </div>

    <form action="{{ route('admin.apbdes.update', $item->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="card-body">
            @include('admin.infografis.apbdes._form')
        </div>
        <div class="card-footer">
            <a href="{{ route('admin.apbdes.index') }}" class="btn btn-secondary">Kembali</a>
            <button class="btn btn-warning" type="submit">Update</button>
        </div>
    </form>
</div>
@endsection