@extends('layouts.app')

@section('content')
<div class="container">
    <h3>Edit Profil</h3>

    <form method="POST" action="{{ route('user.profile.update') }}">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label>Nama Lengkap</label>
            <input type="text" name="nama_lengkap" class="form-control"
                   value="{{ old('nama_lengkap', $user->nama_lengkap) }}">
        </div>

        <div class="mb-3">
            <label>Sekolah</label>
            <input type="text" name="sekolah" class="form-control"
                   value="{{ old('sekolah', $user->sekolah) }}">
        </div>

        <div class="mb-3">
            <label>Alamat</label>
            <textarea name="alamat" class="form-control">{{ old('alamat', $user->alamat) }}</textarea>
        </div>

        <div class="mb-3">
            <label>No HP</label>
            <input type="text" name="no_hp" class="form-control"
                   value="{{ old('no_hp', $user->no_hp) }}">
        </div>

        <button class="btn btn-success">Simpan</button>
        <a href="{{ route('user.profile.show') }}" class="btn btn-secondary">Batal</a>
    </form>
</div>
@endsection