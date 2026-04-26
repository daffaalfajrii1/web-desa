@extends('layouts.admin')

@section('title', 'Edit Role')
@section('page_title', 'Edit Role')

@section('content')
<div class="card card-warning">
    <div class="card-header">
        <h3 class="card-title">Edit Role</h3>
    </div>

    <form action="{{ route('admin.roles.update', $role->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="card-body">
            @include('admin.roles._form')
        </div>

        <div class="card-footer">
            <a href="{{ route('admin.roles.index') }}" class="btn btn-secondary">Kembali</a>
            <button type="submit" class="btn btn-warning">Update</button>
        </div>
    </form>
</div>
@endsection