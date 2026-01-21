@extends('layouts.admin')
@section('title', 'Edit Sekolah')
@section('header', 'Edit Data Sekolah')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card card-warning">
            <div class="card-header">
                <h3 class="card-title">Form Edit Sekolah</h3>
            </div>
            <form action="{{ route('sekolah.update', $school->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="card-body">

                    <div class="form-group">
                        <label>NPSN</label>
                        <input type="number" name="npsn" class="form-control @error('npsn') is-invalid @enderror" value="{{ old('npsn', $school->npsn) }}" required>
                        @error('npsn')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label>Nama Sekolah</label>
                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $school->name) }}" required>
                        @error('name')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label>Telepon</label>
                        <input type="text" name="phone" class="form-control @error('phone') is-invalid @enderror" value="{{ old('phone', $school->phone) }}" required>
                        @error('phone')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label>Alamat</label>
                        <textarea name="address" class="form-control @error('address') is-invalid @enderror" rows="3" required>{{ old('address', $school->address) }}</textarea>
                        @error('address')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label>Email Sekolah</label>
                        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', $school->email) }}">
                        @error('email')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label>Status Berlangganan</label>
                        <select name="subscription_status" class="form-control">
                            <option value="active" {{ $school->subscription_status == 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ $school->subscription_status == 'inactive' ? 'selected' : '' }}>Inactive</option>
                        </select>
                    </div>

                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Update</button>
                    <a href="{{ route('superadmin.sekolah.index') }}" class="btn btn-secondary">Kembali</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
