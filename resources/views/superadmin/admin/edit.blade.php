@extends('layouts.admin')

@section('title', 'Edit Admin Internal')
@section('header', 'Edit Data Admin Internal')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card card-warning">
            <div class="card-header">
                <h3 class="card-title">Form Edit Admin</h3>
            </div>
            <form action="{{ route('superadmin.admin.update', $user->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="card-body">

                    <div class="form-group">
                        <label for="full_name">Nama Lengkap</label>
                        <input type="text" name="full_name" class="form-control @error('full_name') is-invalid @enderror" id="full_name" value="{{ old('full_name', $user->full_name) }}" required>
                        @error('full_name')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="username">Username</label>
                        <input type="text" name="username" class="form-control @error('username') is-invalid @enderror" id="username" value="{{ old('username', $user->username) }}" required>
                        @error('username')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="email">Alamat Email</label>
                        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" id="email" value="{{ old('email', $user->email) }}" required>
                        @error('email')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="password">Password Baru <small class="text-muted">(Kosongkan jika tidak ingin mengganti)</small></label>
                        <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" id="password" placeholder="Masukkan password baru (opsional)">
                        @error('password')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-warning"><i class="fas fa-edit"></i> Update</button>
                    <a href="{{ route('superadmin.admin.index') }}" class="btn btn-secondary">Batal</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
