@extends('layouts.admin_course')

@section('title', 'Dashboard Admin')

@section('content_header')
    <h1>Dashboard {{ Auth::user()->role === 'school_admin' ? 'Sekolah' : 'General' }}</h1>
@stop

@section('content')
<div class="container-fluid">
    {{-- Statistik Row --}}
    <div class="row">
        <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box shadow-sm">
                <span class="info-box-icon bg-info elevation-1"><i class="fas fa-book"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Total Kursus</span>
                    <span class="info-box-number">{{ $totalCourses ?? 0 }}</span>
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box mb-3 shadow-sm">
                <span class="info-box-icon bg-warning elevation-1"><i class="fas fa-users"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Siswa Terdaftar</span>
                    <span class="info-box-number">{{ $totalStudents ?? 0 }}</span>
                </div>
            </div>
        </div>
    </div>

    {{-- Manajemen Pembelajaran Row --}}
    <div class="row">
        <div class="col-md-12">
            <div class="card card-primary card-outline shadow-sm">
                <div class="card-header bg-white">
                    <h3 class="card-title fw-bold">
                        <i class="fas fa-edit me-2 text-primary"></i> Manajemen Pembelajaran
                    </h3>
                </div>
                <div class="card-body">
                    <p class="lead">Selamat datang, <b class="text-primary">{{ Auth::user()->full_name }}</b>.</p>
                    
                    @if(Auth::user()->school)
                        <p class="text-muted">Anda mengelola sekolah: <b>{{ Auth::user()->school->name }}</b>.</p>
                    @else
                        <p class="text-muted">Anda masuk sebagai: <b>Admin General (Pusat)</b>.</p>
                    @endif

                    <div class="row mt-4">
                        {{-- Tombol Kelola Kursus (Ukurannya menyesuaikan role) --}}
                        <div class="{{ Auth::user()->school ? 'col-md-6' : 'col-md-12' }} mb-3">
                            <a href="{{ route('courses.index') }}" class="btn btn-app bg-info border-0 shadow-sm w-100 p-4" style="height: auto; border-radius: 12px;">
                                <i class="fas fa-layer-group mb-3" style="font-size: 2.5rem; display: block;"></i>
                                <span style="font-size: 1.2rem; font-weight: bold;">Kelola Kursus & Modul</span>
                                <div class="mt-2 text-sm opacity-75">Kelola materi pembelajaran {{ Auth::user()->school ? 'sekolah Anda' : 'pusat' }}.</div>
                            </a>
                        </div>

                        {{-- Widget Token Registrasi (Hanya muncul untuk Admin Sekolah) --}}
                        @if(Auth::user()->school)
                        <div class="col-md-6 mb-3">
                            <div class="card shadow-sm border h-100" style="border-radius: 12px;">
                                <div class="card-body text-center d-flex flex-column justify-content-center">
                                    <h6 class="text-muted fw-bold">TOKEN REGISTRASI SISWA</h6>
                                    
                                    <div class="bg-light p-3 rounded my-2 border">
                                        <h2 class="fw-bold mb-0 text-primary" id="schoolToken">
                                            {{ Auth::user()->school->token_code ?? 'BELUM ADA' }}
                                        </h2>
                                    </div>

                                    <div class="d-flex justify-content-center gap-2 mt-2">
                                        <form action="{{ route('school_admin.generate_token') }}" method="POST">
                                            @csrf
                                            <button type="submit" class="btn btn-primary btn-sm px-3 shadow-sm">
                                                <i class="fas fa-sync-alt me-1"></i> Generate Baru
                                            </button>
                                        </form>
                                        
                                        @if(Auth::user()->school->token_code)
                                            <button type="button" class="btn btn-outline-secondary btn-sm px-3 shadow-sm" onclick="copyToken()">
                                                <i class="fas fa-copy me-1"></i> Salin Kode
                                            </button>
                                        @endif
                                    </div>
                                    <p class="small text-muted mt-3 mb-0">Siswa menggunakan kode ini untuk bergabung otomatis ke sekolah Anda.</p>
                                </div>
                            </div>
                        </div>
                        @endif
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
            var tokenElement = document.getElementById("schoolToken");
            if(tokenElement) {
                var tokenText = tokenElement.innerText.trim();
                if(tokenText !== "BELUM ADA") {
                    navigator.clipboard.writeText(tokenText).then(() => {
                        alert("Token " + tokenText + " berhasil disalin!");
                    });
                }
            }
        }
    </script>
@stop