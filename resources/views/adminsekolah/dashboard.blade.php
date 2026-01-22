@extends('layouts.admin_course')

@section('title', 'Dashboard Admin Sekolah')

@section('content_header')
    <div class="d-flex align-items-center justify-content-between">
        <div class="d-flex align-items-center">
            <i class="fas fa-tachometer-alt fa-2x me-3 text-primary"></i>
            <div>
                <h1 class="fw-bold mb-0">Dashboard Admin Sekolah</h1>
                <p class="text-muted mb-0">{{ Auth::user()->school->name ?? 'Instansi Belum Terhubung' }}</p>
            </div>
        </div>
    </div>
@stop

@section('content')
<div class="container-fluid">
    {{-- Barisan Statistik Singkat --}}
    <div class="row">
        <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box shadow-sm border-0">
                <span class="info-box-icon bg-info elevation-1"><i class="fas fa-book"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Total Kursus</span>
                    <span class="info-box-number text-lg">{{ $totalCourses ?? 0 }}</span>
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box mb-3 shadow-sm border-0">
                <span class="info-box-icon bg-warning elevation-1 text-white"><i class="fas fa-users"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Siswa Terdaftar</span>
                    <span class="info-box-number text-lg">{{ $totalStudents ?? 0 }}</span>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card card-outline card-primary shadow-sm" style="border-radius: 15px;">
                <div class="card-header bg-white border-0 pt-4">
                    <h3 class="card-title fw-bold">
                        <i class="fas fa-tasks me-2 text-primary"></i> Panel Kontrol Admin
                    </h3>
                </div>
                <div class="card-body px-4 pb-4">
                    <p class="lead">Selamat datang kembali, <b class="text-primary">{{ Auth::user()->full_name }}</b>.</p>
                    <hr>

                    <div class="row mt-4">
                        {{-- Sisi Kiri: Kelola Kursus --}}
                        <div class="col-md-6 mb-4">
                            <div class="card h-100 border shadow-none" style="border-radius: 12px; transition: transform 0.2s;">
                                <div class="card-body text-center p-4">
                                    <div class="mb-3">
                                        <i class="fas fa-layer-group fa-3x text-info"></i>
                                    </div>
                                    <h5 class="fw-bold">Manajemen Pembelajaran</h5>
                                    <p class="text-muted small">Kelola kurikulum, bab (module), dan materi koding untuk siswa Anda.</p>
                                    <a href="{{ route('courses.index') }}" class="btn btn-info btn-block shadow-sm">
                                        <i class="fas fa-external-link-alt me-1"></i> Buka Manajemen Kursus
                                    </a>
                                </div>
                            </div>
                        </div>

                        {{-- Sisi Kanan: Token Sekolah --}}
                        <div class="col-md-6 mb-4">
                            <div class="card h-100 border shadow-none" style="border-radius: 12px;">
                                <div class="card-body text-center p-4">
                                    <div class="mb-3">
                                        <i class="fas fa-key fa-3x text-warning"></i>
                                    </div>
                                    <h5 class="fw-bold">Token Akses Siswa</h5>
                                    <p class="text-muted small">Bagikan kode unik ini agar siswa otomatis terhubung dengan sekolah Anda.</p>
                                    
                                    <div class="input-group mb-3 shadow-sm">
                                        <input type="text" id="schoolToken" class="form-control form-control-lg text-center fw-bold text-primary bg-light" 
                                               value="{{ Auth::user()->school->token_code ?? 'BELUM ADA' }}" readonly 
                                               style="letter-spacing: 2px; border: 2px dashed #ced4da;">
                                        <div class="input-group-append">
                                            <button class="btn btn-outline-primary" type="button" onclick="copyToken()" title="Salin Kode">
                                                <i class="fas fa-copy"></i>
                                            </button>
                                        </div>
                                    </div>

                                    <form action="{{ route('school_admin.generate_token') }}" method="POST" onsubmit="return confirm('Generate token baru akan menggantikan token lama. Lanjutkan?')">
                                        @csrf
                                        <button type="submit" class="btn btn-link btn-sm text-primary p-0">
                                            <i class="fas fa-sync-alt me-1"></i> Perbarui Token Baru
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div> {{-- End Row Internal --}}
                </div>
            </div>
        </div>
    </div>
</div>
@stop

@section('js')
    <script> 
        function copyToken() {
            const tokenInput = document.getElementById("schoolToken");
            const tokenValue = tokenInput.value;

            if (tokenValue === "BELUM ADA") {
                alert("Belum ada token untuk disalin.");
                return;
            }

            tokenInput.select();
            tokenInput.setSelectionRange(0, 99999); // Untuk mobile

            navigator.clipboard.writeText(tokenValue).then(() => {
                alert("Token " + tokenValue + " berhasil disalin ke clipboard!");
            }).catch(err => {
                console.error('Gagal menyalin: ', err);
            });
        }

        console.log('Dashboard Admin Sekolah [{{ Auth::user()->school->name ?? "Pusat" }}] Berhasil Dimuat'); 
    </script>
@stop