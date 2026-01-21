@extends('layouts.admin')
@section('title', 'Tambah Sekolah')
@section('header', 'Tambah Sekolah Baru')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card card-primary">
            <div class="card-header">
                <h3 class="card-title">Form Tambah Sekolah</h3>
            </div>
            <form action="{{ route('sekolah.store') }}" method="POST">
                @csrf
                <div class="card-body">

                    <div class="form-group">
                        <label>NPSN</label>
                        <input type="number" name="npsn" class="form-control @error('npsn') is-invalid @enderror" placeholder="Masukkan NPSN" value="{{ old('npsn') }}" required>
                        @error('npsn')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label>Nama Sekolah</label>
                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" placeholder="Nama Sekolah" value="{{ old('name') }}" required>
                        @error('name')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label>Telepon</label>
                        <input type="text" name="phone" class="form-control @error('phone') is-invalid @enderror" placeholder="Nomor Telepon" value="{{ old('phone') }}" required>
                        @error('phone')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label>Alamat</label>
                        <textarea name="address" class="form-control @error('address') is-invalid @enderror" rows="3" placeholder="Alamat Lengkap" required>{{ old('address') }}</textarea>
                        @error('address')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label>Email Sekolah <small>(Opsional)</small></label>
                        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" placeholder="Email Sekolah" value="{{ old('email') }}">
                        @error('email')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-success"><i class="fas fa-save"></i> Simpan</button>
                    <a href="{{ route('superadmin.sekolah.index') }}" class="btn btn-secondary">Kembali</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
