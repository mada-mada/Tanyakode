@extends('layouts.user')

@section('title', 'Edit Profil')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="navy-card shadow-sm">
                <div class="navy-card-header bg-navy">
                    <h5 class="mb-0 fw-bold text-accent">
                        <i class="fa-solid fa-user-gear me-2"></i> Pengaturan Profil
                    </h5>
                </div>
                <div class="navy-card-body">
                    <form action="{{ route('user.profiles.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <div class="col-md-4 text-center border-end mb-4">
                                <div class="mb-3">
                                    <img src="{{ $user->avatar_url ? asset('storage/' . $user->avatar_url) : 'https://ui-avatars.com/api/?name=' . urlencode($user->full_name) }}" 
                                         class="rounded-circle img-thumbnail shadow-sm" style="width: 150px; height: 150px; object-fit: cover;">
                                </div>
                                <div class="form-group text-start">
                                    <label class="small fw-bold text-muted">Ganti Foto Profil</label>
                                    <input type="file" name="avatar" class="form-control form-control-sm">
                                    <small class="text-muted italic">Format: JPG, PNG. Max: 2MB</small>
                                </div>
                            </div>

                            <div class="col-md-8">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label small fw-bold">Nama Lengkap</label>
                                        <input type="text" name="full_name" class="form-control" value="{{ old('full_name', $user->full_name) }}" required>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label small fw-bold">Username</label>
                                        <input type="text" name="username" class="form-control" value="{{ old('username', $user->username) }}" required>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label small fw-bold">Email</label>
                                        <input type="email" name="email" class="form-control" value="{{ old('email', $user->email) }}" required>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label small fw-bold">Domisili</label>
                                        <input type="text" name="domisili" class="form-control" value="{{ old('domisili', $user->domisili) }}">
                                    </div>
                                </div>

                                <h6 class="mt-4 mb-3 fw-bold text-navy border-bottom pb-2">Informasi Akademik</h6>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label small fw-bold">NIS</label>
                                        <input type="text" name="nis" class="form-control" value="{{ old('nis', $user->nis) }}">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label small fw-bold">NISN</label>
                                        <input type="text" name="nisn" class="form-control" value="{{ old('nisn', $user->nisn) }}">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label small fw-bold">Kelas</label>
                                        <select name="grade" class="form-select">
                                            <option value="1" {{ $user->grade == '1' ? 'selected' : '' }}>Kelas 1</option>
                                            <option value="2" {{ $user->grade == '2' ? 'selected' : '' }}>Kelas 2</option>
                                            <option value="3" {{ $user->grade == '3' ? 'selected' : '' }}>Kelas 3</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label small fw-bold">Kategori Sekolah</label>
                                        <select name="school_category" class="form-select">
                                            <option value="SMP" {{ $user->school_category == 'SMP' ? 'selected' : '' }}>SMP</option>
                                            <option value="SMA" {{ $user->school_category == 'SMA' ? 'selected' : '' }}>SMA</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="mt-4 d-flex gap-2">
                                    <button type="submit" class="btn btn-navy px-4">
                                        <i class="fa-solid fa-save me-2"></i>Simpan Perubahan
                                    </button>
                                    <a href="{{ route('user.profiles.show') }}" class="btn btn-outline-secondary">
                                        Batal
                                    </a>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .btn-navy { background-color: #0a192f; color: #64ffda; border: none; }
    .btn-navy:hover { background-color: #112240; color: #64ffda; transform: translateY(-2px); transition: 0.3s; }
    .text-navy { color: #0a192f !important; }
    .text-accent { color: #64ffda !important; }
    .bg-navy { background-color: #0a192f !important; }
</style>
@endsection