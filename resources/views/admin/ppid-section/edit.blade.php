@extends('layouts.admin')
@section('title', 'Edit Section PPID')
@section('page_title', 'Edit Section PPID')

@section('content')
<div class="card card-warning">
    <div class="card-header"><h3 class="card-title">Edit Section PPID</h3></div>
    <form action="{{ route('admin.ppid-section.update', $item->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="card-body">
            @include('admin.ppid-section._form')
        </div>
        <div class="card-footer">
            <a href="{{ route('admin.ppid-section.index') }}" class="btn btn-secondary">Kembali</a>
            <button type="submit" class="btn btn-warning">Update</button>
        </div>
    </form>
</div>
@endsection