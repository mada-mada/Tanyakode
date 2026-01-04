@extends('layouts.admin')

@section('title', 'Dashboard Admin')

@section('content_header')
    <h1>Dashboard</h1>
@stop

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box">
                <span class="info-box-icon bg-info elevation-1"><i class="fas fa-book"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Total Kursus</span>
                    <span class="info-box-number">
                        {{ $totalCourses ?? 0 }}
                    </span>
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box mb-3">
                <span class="info-box-icon bg-warning elevation-1"><i class="fas fa-users"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Siswa Aktif</span>
                    <span class="info-box-number">0</span> </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card card-primary card-outline">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-edit"></i> Manajemen Pembelajaran
                    </h3>
                </div>
                <div class="card-body">
                    <p>Selamat datang, <b>{{ Auth::user()->name }}</b>. Silakan pilih menu di bawah ini untuk mengelola materi pembelajaran.</p>

                    <div class="row">
                        <div class="col-md-4">
                            <a href="{{ route('courses.index') }}" class="btn btn-app bg-info" style="width: 100%; height: auto; padding: 20px;">
                                <i class="fas fa-layer-group" style="font-size: 2rem; display: block; margin-bottom: 10px;"></i>
                                <span style="font-size: 1.2rem; font-weight: bold;">Kelola Kursus & Modul</span>
                                <div class="mt-2 text-sm">Buat kursus, tambah bab, dan isi materi.</div>
                            </a>
                        </div>

                        </div>
                </div>
            </div>
        </div>
    </div>
</div>
@stop

@section('css')
    @stop

@section('js')
    <script> console.log('Dashboard Admin Loaded'); </script>
@stop
