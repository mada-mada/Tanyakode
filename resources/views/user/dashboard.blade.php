@extends('layouts.user')

@section('title', 'Dashboard User')

@section('content')
@php
$notifications = DB::table('notifications')
    ->where('notifiable_id', auth()->id())
    ->orderBy('created_at', 'desc')
    ->get();

$userEnrollments = DB::table('course_enrollments')
    ->where('user_id', auth()->id())
    ->orderBy('updated_at', 'desc')
    ->get();
@endphp

<!-- HEADER -->
<div class="d-flex justify-content-between align-items-center mb-5">
    <div>
        <h1 class="fw-bold text-navy mb-2" style="color: var(--navy-dark);">
            Selamat Datang, <span class="text-accent">{{ Auth::user()->full_name ?? Auth::user()->username }}</span>
        </h1>
        <p class="text-muted">
            Teruslah belajar dan raih pencapaian baru hari ini
        </p>
    </div>
    <div class="text-end">
        <span class="badge badge-navy">Student</span>
        <div class="mt-2">
            <small class="text-muted">Terakhir login: {{ now()->format('H:i') }}</small>
        </div>
    </div>
</div>

<!-- LINK PROFIL -->
<div class="mb-4">
    {{-- <a href="{{ route('user.profile.show') }}" class="btn btn-outline-navy"> --}}
        <i class="fa-solid fa-user me-2"></i> Profil Saya
    </a>
</div>

<!-- NOTIFIKASI -->
@if($notifications->count() > 0)
<div class="row mb-5">
    <div class="col-12">
        <div class="navy-card delay-1">
            <div class="navy-card-header d-flex justify-content-between">
                <h5 class="mb-0 fw-bold">
                    <i class="fa-solid fa-bell me-2"></i>
                    Notifikasi
                </h5>
                <form method="POST" action="/notifications/read-all">
                    @csrf
                    <button class="btn btn-sm btn-secondary">Tandai Dibaca</button>
                </form>
            </div>
            <div class="navy-card-body">
                @foreach($notifications as $n)
                    @php $d = json_decode($n->data); @endphp
                    @if($n->type === 'course_completed')
                        <div class="alert alert-success">
                            <strong>{{ $d->title }}</strong><br>
                            {{ $d->message }}
                            <div class="mt-2">
                                <a href="/certificate/{{ $d->course_id }}" class="btn btn-sm btn-primary">
                                    <i class="fa-solid fa-download me-1"></i> Download Sertifikat
                                </a>
                            </div>
                        </div>
                    @else
                        <div class="alert alert-info">
                            <strong>{{ $d->title ?? 'Notifikasi' }}</strong><br>
                            {{ $d->message ?? 'Pesan notifikasi' }}
                        </div>
                    @endif
                @endforeach
            </div>
        </div>
    </div>
</div>
@endif

<!-- STATISTICS -->
<div class="row g-4 mb-5">
    <div class="col-md-3">
        <div class="stat-card delay-1">
            <div class="d-flex align-items-center">
                <div class="rounded-circle p-3 me-3" style="background: rgba(10, 25, 47, 0.1);">
                    <i class="fa-solid fa-book text-navy" style="color: var(--navy-dark);"></i>
                </div>
                <div>
                    <h3 class="stat-number fw-bold mb-0" style="color: var(--navy-dark);">
                        {{ $userEnrollments->count() }}
                    </h3>
                    <small class="text-muted">Kursus Aktif</small>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card delay-2">
            <div class="d-flex align-items-center">
                <div class="rounded-circle p-3 me-3" style="background: rgba(100, 255, 218, 0.1);">
                    <i class="fa-solid fa-chart-line text-accent" style="color: var(--accent-blue);"></i>
                </div>
                <div>
                    <h3 class="stat-number fw-bold mb-0" style="color: var(--navy-dark);">
                        @php
                            $avgProgress = $userEnrollments->avg('progress_percentage') ?? 0;
                            echo round($avgProgress, 1) . '%';
                        @endphp
                    </h3>
                    <small class="text-muted">Progress Belajar</small>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card delay-3">
            <div class="d-flex align-items-center">
                <div class="rounded-circle p-3 me-3" style="background: rgba(10, 25, 47, 0.1);">
                    <i class="fa-solid fa-clock text-navy" style="color: var(--navy-dark);"></i>
                </div>
                <div>
                    <h3 class="stat-number fw-bold mb-0" style="color: var(--navy-dark);">18</h3>
                    <small class="text-muted">Jam Belajar</small>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card delay-4">
            <div class="d-flex align-items-center">
                <div class="rounded-circle p-3 me-3" style="background: rgba(100, 255, 218, 0.1);">
                    <i class="fa-solid fa-certificate text-accent" style="color: var(--accent-blue);"></i>
                </div>
                <div>
                    <h3 class="stat-number fw-bold mb-0" style="color: var(--navy-dark);">
                        {{ $userEnrollments->where('status', 'completed')->count() }}
                    </h3>
                    <small class="text-muted">Sertifikat</small>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- KURSUS AKTIF -->
@if($userEnrollments->count() > 0)
<div class="row g-4 mb-5">
    <div class="col-12">
        <div class="navy-card delay-1">
            <div class="navy-card-header">
                <h5 class="mb-0 fw-bold">
                    <i class="fa-solid fa-play-circle me-2"></i>
                    Kursus yang Sedang Dipelajari
                </h5>
            </div>
            <div class="navy-card-body">
                <div class="row g-4">
                    @foreach($userEnrollments as $enrollment)
                        @php
                            $course = DB::table('courses')->where('id', $enrollment->course_id)->first();
                        @endphp
                        @if($course && $enrollment->status !== 'completed')
                        <div class="col-md-6">
                            <div class="border rounded-3 p-4 h-100">
                                <div class="d-flex justify-content-between align-items-start mb-3">
                                    <div>
                                        <span class="badge badge-navy mb-2">{{ $course->category ?? 'Course' }}</span>
                                        <h6 class="fw-bold mb-1">{{ $course->title }}</h6>
                                        <small class="text-muted">Level: {{ $course->level ?? 'Basic' }}</small>
                                    </div>
                                    <i class="fa-solid fa-code text-accent fa-lg"></i>
                                </div>
                                <div class="mb-3">
                                    <div class="d-flex justify-content-between mb-1">
                                        <small class="text-muted">Progress</small>
                                        <small class="fw-bold" style="color: var(--navy-dark);">{{ $enrollment->progress_percentage }}%</small>
                                    </div>
                                    <div class="progress-navy">
                                        <div class="progress-bar-navy" data-width="{{ $enrollment->progress_percentage }}"></div>
                                    </div>
                                </div>
                                <a href="{{ route('user.courses.show', $course->slug) }}" class="btn btn-navy w-100">
                                    <i class="fa-solid fa-play me-2"></i> Lanjutkan Belajar
                                </a>
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
    .text-navy { color: var(--navy-dark) !important; }
    .text-accent { color: var(--accent-blue) !important; }
    
    .stat-card {
        animation: slide-in-up 0.6s ease-out;
        animation-fill-mode: both;
    }
    
    .border {
        border-color: rgba(10, 25, 47, 0.1) !important;
        transition: all 0.3s ease;
    }
    
    .border:hover {
        border-color: var(--accent-blue) !important;
        box-shadow: 0 5px 15px rgba(10, 25, 47, 0.1);
    }
    
    .btn-outline-navy {
        border: 1px solid var(--navy-dark);
        color: var(--navy-dark);
        background: transparent;
    }
    
    .btn-outline-navy:hover {
        background: var(--navy-dark);
        color: white;
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const animatedElements = document.querySelectorAll('.navy-card, .stat-card');
        
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.style.opacity = '1';
                    entry.target.style.transform = 'translateY(0)';
                }
            });
        }, { threshold: 0.1 });
        
        animatedElements.forEach(element => {
            element.style.opacity = '0';
            element.style.transform = 'translateY(20px)';
            element.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
            observer.observe(element);
        });
        
        const progressBars = document.querySelectorAll('.progress-bar-navy')
        progressBars.forEach(bar => {
            const width = bar.getAttribute('data-width')
            bar.style.width = width + '%'
        });
    });
</script>

@endsection