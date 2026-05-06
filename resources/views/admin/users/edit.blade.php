@extends('layouts.admin')

@section('title', 'Edit User')
@section('page_title', 'Edit User')

@section('content')
<div class="card card-warning mb-3">
    <div class="card-header">
        <h3 class="card-title">Edit User</h3>
    </div>

    <form action="{{ route('admin.users.update', $user->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="card-body">
            @include('admin.users._form')
        </div>

        <div class="card-footer">
            <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">Kembali</a>
            <button type="submit" class="btn btn-warning">Update</button>
        </div>
    </form>
</div>

<div class="card card-danger">
    <div class="card-header">
        <h3 class="card-title">Reset Password</h3>
    </div>

    <form action="{{ route('admin.users.reset-password', $user->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Password Baru <span class="text-danger">*</span></label>
                        <input type="password" name="password" required class="form-control">
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label>Konfirmasi Password Baru <span class="text-danger">*</span></label>
                        <input type="password" name="password_confirmation" required class="form-control">
                    </div>
                </div>
            </div>
        </div>

        <div class="card-footer">
            <button type="submit" class="btn btn-danger">Reset Password</button>
        </div>
    </form>
</div>
@endsection