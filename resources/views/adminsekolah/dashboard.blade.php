@extends('layouts.admin_course')

@section('title', 'Dashboard Admin Sekolah')

@section('content_header')
    <div class="d-flex align-items-center">
        <i class="fas fa-tachometer-alt me-2 text-primary"></i>
        <h1 class="fw-bold mb-0">Dashboard Admin Sekolah</h1>
    </div>
@stop

@section('content')
<div class="container-fluid">
    <div class="row">
        {{-- Total Kursus Milik Sekolah --}}
        <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box shadow-sm">
                <span class="info-box-icon bg-info elevation-1"><i class="fas fa-book"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Total Kursus</span>
                    <span class="info-box-number">
                        {{ $totalCourses ?? 0 }}
                    </span>
                </div>
            </div>
        </div>

        {{-- Total Siswa Terdaftar di Sekolah --}}
        <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box mb-3 shadow-sm">
                <span class="info-box-icon bg-warning elevation-1"><i class="fas fa-users"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Siswa Terdaftar</span>
                    <span class="info-box-number">
                        {{ $totalStudents ?? 0 }}
                    </span>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card card-primary card-outline shadow-sm">
                <div class="card-header bg-white">
                    <h3 class="card-title fw-bold">
                        <i class="fas fa-edit me-2 text-primary"></i> Manajemen Pembelajaran
                    </h3>
                </div>
                <div class="card-body">
                    {{-- Menggunakan full_name sesuai Model User --}}
                    <p class="lead">Selamat datang, <b class="text-primary">{{ Auth::user()->full_name }}</b>.</p>
                    <p class="text-muted">Anda mengelola kursus untuk <b>{{ Auth::user()->school->name ?? 'Sekolah' }}</b>. Silakan pilih menu di bawah ini untuk mengelola materi pembelajaran.</p>

                    <div class="row mt-4">
                        <div class="col-md-4">
                            {{-- Route diarahkan ke index kursus milik sekolah --}}
                            <a href="{{ route('courses.index') }}" class="btn btn-app bg-info border-0 shadow-sm" style="width: 100%; height: auto; padding: 25px; border-radius: 12px;">
                                <i class="fas fa-layer-group mb-3" style="font-size: 2.5rem; display: block;"></i>
                                <span style="font-size: 1.2rem; font-weight: bold;">Kelola Kursus & Modul</span>
                                <div class="mt-2 text-sm opacity-75">Kelola bab (module) dan materi (content) koding.</div>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@stop

@section('js')
    <script> 
        console.log('Dashboard Admin Sekolah [{{ Auth::user()->school->name ?? "Pusat" }}] Berhasil Dimuat'); 
    </script>
@stop