@extends('layouts.user')

@section('title', 'Dashboard User')

@section('content')
@php
    // Mengambil data notifikasi dan pendaftaran kursus
    $notifications = DB::table('notifications')
        ->where('notifiable_id', auth()->id())
        ->orderBy('created_at', 'desc')
        ->get();

    $userEnrollments = DB::table('course_enrollments')
        ->where('user_id', auth()->id())
        ->orderBy('updated_at', 'desc')
        ->get();
@endphp

<div class="d-flex justify-content-between align-items-center mb-5">
    <div>
        <h1 class="fw-bold text-navy mb-2">
            Selamat Datang, <span class="text-navy">{{ Auth::user()->full_name ?? Auth::user()->username }}</span>
        </h1>
        <p class="text-muted">Teruslah belajar dan raih pencapaian baru hari ini</p>
    </div>
    <div class="text-end">
        <span class="badge badge-navy">Student</span>
        <div class="mt-2">
            <small class="text-muted">Terakhir login: {{ now()->format('H:i') }}</small>
        </div>
    </div>
</div>

<div class="row g-4 mb-5">
    <div class="col-md-3">
        <div class="stat-card delay-1 shadow-sm">
            <div class="d-flex align-items-center">
                <div class="rounded-circle p-3 me-3" style="background: rgba(10, 25, 47, 0.1);">
                    <i class="fa-solid fa-book text-navy"></i>
                </div>
                <div>
                    <h3 class="stat-number fw-bold mb-0 text-navy">{{ $userEnrollments->count() }}</h3>
                    <small class="text-muted">Kursus Aktif</small>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card delay-2 shadow-sm">
            <div class="d-flex align-items-center">
                <div class="rounded-circle p-3 me-3" style="background: rgba(100, 255, 218, 0.1);">
                    <i class="fa-solid fa-chart-line text-navy"></i>
                </div>
                <div>
                    <h3 class="stat-number fw-bold mb-0 text-navy">
                        {{ round($userEnrollments->avg('progress_percentage') ?? 0, 1) }}%
                    </h3>
                    <small class="text-muted">Progress Belajar</small>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card delay-3 shadow-sm">
            <div class="d-flex align-items-center">
                <div class="rounded-circle p-3 me-3" style="background: rgba(10, 25, 47, 0.1);">
                    <i class="fa-solid fa-clock text-navy"></i>
                </div>
                <div>
                    <h3 class="stat-number fw-bold mb-0 text-navy">18</h3>
                    <small class="text-muted">Jam Belajar</small>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card delay-4 shadow-sm">
            <div class="d-flex align-items-center">
                <div class="rounded-circle p-3 me-3" style="background: rgba(100, 255, 218, 0.1);">
                    <i class="fa-solid fa-certificate text-navy"></i>
                </div>
                <div>
                    <h3 class="stat-number fw-bold mb-0 text-navy">
                        {{ $userEnrollments->where('status', 'completed')->count() }}
                    </h3>
                    <small class="text-muted">Sertifikat</small>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row mb-5">
    <div class="col-12">
        <div class="navy-card delay-2">
            <div class="navy-card-header bg-navy border-0">
                {{-- Header menggunakan warna yang sama dengan tombol --}}
                <h5 class="mb-0 fw-bold text-accent">
                    <i class="fa-solid fa-school me-2"></i> Instansi Sekolah
                </h5>
            </div>
            <div class="navy-card-body">
                @if(Auth::user()->school_id)
                    <div class="d-flex align-items-center p-3 border rounded-3 bg-light shadow-sm">
                        <div class="rounded-circle bg-navy p-3 me-3 text-white">
                            <i class="fa-solid fa-building-columns text-accent"></i>
                        </div>
                        <div>
                            <h6 class="fw-bold mb-0 text-navy">{{ Auth::user()->school->name }}</h6>
                            <small class="text-muted">Status: Siswa Aktif</small>
                        </div>
                        <div class="ms-auto">
                            <button class="btn btn-sm btn-outline-navy" onclick="toggleJoinForm()">
                                <i class="fa-solid fa-exchange-alt me-1"></i> Ganti Kode
                            </button>
                        </div>
                    </div>
                @endif

                <div id="joinForm" class="{{ Auth::user()->school_id ? 'd-none mt-4' : '' }}">
                    <p class="text-muted small mb-3">Masukkan <strong>Token Sekolah</strong> untuk terhubung dengan instansi Anda.</p>
                    <form action="{{ route('user.join_school') }}" method="POST">
                        @csrf
                        <div class="input-group">
                            <span class="input-group-text bg-white border-end-0">
                                <i class="fa-solid fa-key text-accent"></i>
                            </span>
                            <input type="text" name="token_code" class="form-control border-start-0 shadow-none" placeholder="Masukkan Kode Unik Sekolah" required>
                            <button type="submit" class="btn btn-navy px-4">Gabung Sekarang</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@if($userEnrollments->count() > 0)
<div class="row g-4 mb-5">
    <div class="col-12">
        <div class="navy-card delay-1">
            <div class="navy-card-header bg-navy border-0">
                <h5 class="mb-0 fw-bold text-accent">
                    <i class="fa-solid fa-play-circle me-2"></i> Kursus yang Sedang Dipelajari
                </h5>
            </div>
            <div class="navy-card-body">
                <div class="row g-4">
                    @foreach($userEnrollments as $enrollment)
                        @php $course = DB::table('courses')->where('id', $enrollment->course_id)->first(); @endphp
                        @if($course && $enrollment->status !== 'completed')
                        <div class="col-md-6">
                            <div class="border rounded-3 p-4 h-100 transition-card">
                                <div class="d-flex justify-content-between align-items-start mb-3">
                                    <div>
                                        <span class="badge badge-navy mb-2 text-uppercase">{{ $course->level ?? 'Course' }}</span>
                                        <h6 class="fw-bold mb-1 text-navy">{{ $course->title }}</h6>
                                    </div>
                                    <i class="fa-solid fa-code text-navy fa-lg"></i>
                                </div>
                                <div class="mb-3">
                                    <div class="d-flex justify-content-between mb-1">
                                        <small class="text-muted">Progress</small>
                                        <small class="fw-bold text-navy">{{ $enrollment->progress_percentage }}%</small>
                                    </div>
                                    <div class="progress-navy">
                                        <div class="progress-bar-navy" data-width="{{ $enrollment->progress_percentage }}"></div>
                                    </div>
                                </div>
                                <a href="{{ route('user.courses.show', $course->slug) }}" class="btn btn-navy w-100">Lanjutkan Belajar</a>
                            </div>
                        </div>
                        @endif
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endif

<style>
    :root {
        --navy-dark: #0a192f;
        --accent-blue: #64ffda;
    }
    
    /* Utility Classes */
    .text-navy { color: var(--navy-dark) !important; }
    .text-accent { color: var(--accent-blue) !important; }
    .bg-navy { background-color: var(--navy-dark) !important; }
    
    /* Buttons */
    .btn-navy { background-color: var(--navy-dark); color: var(--accent-blue); border: none; transition: 0.3s; }
    .btn-navy:hover { background-color: #172a45; color: var(--accent-blue); transform: translateY(-2px); }
    .btn-outline-navy { border: 1px solid var(--navy-dark); color: var(--navy-dark); background: transparent; }
    .btn-outline-navy:hover { background: var(--navy-dark); color: var(--accent-blue); }

    /* Cards */
    .stat-card { background: white; border-radius: 12px; padding: 1.5rem; transition: 0.3s; }
    .navy-card { background: white; border-radius: 15px; box-shadow: 0 10px 30px rgba(10, 25, 47, 0.05); overflow: hidden; }
    
    /* Header Styling Baru */
    .navy-card-header { 
        padding: 1.2rem 1.5rem; 
        background-color: var(--navy-dark); /* Mengikuti warna tombol */
    }

    .navy-card-body { padding: 1.5rem; }

    /* Progress Bar */
    .progress-navy { height: 8px; background: #e9ecef; border-radius: 10px; }
    .progress-bar-navy { height: 100%; background: var(--navy-dark); border-radius: 10px; transition: width 1s ease-in-out; }

    .badge-navy { background: var(--navy-dark); color: var(--accent-blue); }
    
    .transition-card:hover {
        border-color: var(--accent-blue) !important;
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(10, 25, 47, 0.1);
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const progressBars = document.querySelectorAll('.progress-bar-navy');
        setTimeout(() => {
            progressBars.forEach(bar => {
                bar.style.width = bar.getAttribute('data-width') + '%';
            });
        }, 300);
    });

    function toggleJoinForm() {
        const form = document.getElementById('joinForm');
        form.classList.toggle('d-none');
    }
</script>
@endsection