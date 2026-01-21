@extends('layouts.admin')
@section('title', 'Edit Admin Sekolah')
@section('header', 'Edit Admin Sekolah')

@section('content')
<div class="card">
    <div class="card-body">
        <form action="{{ route('superadmin.adminsekolah.update', $adminsekolah->id) }}" method="POST">
            @csrf @method('PUT')
            <div class="form-group">
                <label>Nama Lengkap</label>
                <input type="text" name="full_name" class="form-control" value="{{ $adminsekolah->full_name }}" required>
            </div>
            <div class="form-group">
                <label>Username</label>
                <input type="text" name="username" class="form-control" value="{{ $adminsekolah->username }}" required>
            </div>
            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" class="form-control" value="{{ $adminsekolah->email }}" required>
            </div>
            <div class="form-group">
                <label>Password (Isi jika ingin mengganti)</label>
                <input type="password" name="password" class="form-control">
            </div>
            <div class="form-group">
                <label>Pilih Sekolah</label>
                <select name="school_id" class="form-control" required>
                    @foreach($sekolah as $s)
                        <option value="{{ $s->id }}" {{ $adminsekolah->school_id == $s->id ? 'selected' : '' }}>
                            {{ $s->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Update</button>
        </form>
    </div>
</div>
@endsection
