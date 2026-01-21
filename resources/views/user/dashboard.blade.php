@extends('layouts.user')

@section('title', 'Dashboard User')

@section('content')

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
            <small class="text-muted">Terakhir login: Hari ini, 08:30</small>
        </div>
    </div>
</div>

<!-- STATISTICS -->
<div class="row g-4 mb-5">
    <div class="col-md-3">
        <div class="stat-card delay-1">
            <div class="d-flex align-items-center">
                <div class="rounded-circle p-3 me-3" style="background: rgba(10, 25, 47, 0.1);">
                    <i class="fa-solid fa-book text-navy" style="color: var(--navy-dark);"></i>
                </div>
                <div>
                    <h3 class="stat-number fw-bold mb-0" style="color: var(--navy-dark);">5</h3>
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
                    <h3 class="stat-number fw-bold mb-0" style="color: var(--navy-dark);">72%</h3>
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
                    <h3 class="stat-number fw-bold mb-0" style="color: var(--navy-dark);">3</h3>
                    <small class="text-muted">Sertifikat</small>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    <!-- KURSUS AKTIF -->
    <div class="col-lg-8">
        <div class="navy-card delay-1">
            <div class="navy-card-header">
                <h5 class="mb-0 fw-bold">
                    <i class="fa-solid fa-play-circle me-2"></i>
                    Kursus yang Sedang Dipelajari
                </h5>
            </div>
            <div class="navy-card-body">
                <div class="row g-4">
                    <div class="col-md-6">
                        <div class="border rounded-3 p-4 h-100">
                            <div class="d-flex justify-content-between align-items-start mb-3">
                                <div>
                                    <span class="badge badge-navy mb-2">Web Development</span>
                                    <h6 class="fw-bold mb-1">Fullstack JavaScript</h6>
                                    <small class="text-muted">Level: Advanced • 15 Modul</small>
                                </div>
                                <i class="fa-solid fa-code text-accent fa-lg"></i>
                            </div>
                            <div class="mb-3">
                                <div class="d-flex justify-content-between mb-1">
                                    <small class="text-muted">Progress</small>
                                    <small class="fw-bold" style="color: var(--navy-dark);">68%</small>
                                </div>
                                <div class="progress-navy">
                                    <div class="progress-bar-navy" data-width="68"></div>
                                </div>
                            </div>
                            <button class="btn btn-navy w-100">
                                <i class="fa-solid fa-play me-2"></i> Lanjutkan Belajar
                            </button>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="border rounded-3 p-4 h-100">
                            <div class="d-flex justify-content-between align-items-start mb-3">
                                <div>
                                    <span class="badge badge-accent mb-2">Mobile</span>
                                    <h6 class="fw-bold mb-1">Flutter Development</h6>
                                    <small class="text-muted">Level: Intermediate • 10 Modul</small>
                                </div>
                                <i class="fa-solid fa-mobile-screen text-navy fa-lg" style="color: var(--navy-dark);"></i>
                            </div>
                            <div class="mb-3">
                                <div class="d-flex justify-content-between mb-1">
                                    <small class="text-muted">Progress</small>
                                    <small class="fw-bold" style="color: var(--navy-dark);">42%</small>
                                </div>
                                <div class="progress-navy">
                                    <div class="progress-bar-navy" data-width="42"></div>
                                </div>
                            </div>
                            <button class="btn btn-accent w-100">
                                <i class="fa-solid fa-play me-2"></i> Lanjutkan Belajar
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- STATUS PEMBAYARAN -->
    <div class="col-lg-4">
        <div class="navy-card delay-2">
            <div class="navy-card-header">
                <h5 class="mb-0 fw-bold">
                    <i class="fa-solid fa-wallet me-2"></i>
                    Status Pembayaran
                </h5>
            </div>
            <div class="navy-card-body">
                <div class="text-center mb-4">
                    <div class="mb-3">
                        <div class="rounded-circle d-inline-flex align-items-center justify-content-center p-4" 
                             style="background: rgba(100, 255, 218, 0.1); width: 80px; height: 80px;">
                            <i class="fa-solid fa-check-circle text-accent fa-2x" style="color: var(--accent-blue);"></i>
                        </div>
                    </div>
                    <h4 class="fw-bold mb-2" style="color: var(--navy-dark);">LUNAS</h4>
                    <p class="text-muted mb-4">Semua pembayaran telah terkonfirmasi</p>
                </div>
                
                <div class="timeline">
                    <div class="timeline-item">
                        <h6 class="fw-bold mb-1" style="color: var(--navy-dark);">Fullstack JavaScript</h6>
                        <small class="text-muted">Pembayaran terakhir: 15 Jan 2024</small>
                        <span class="badge badge-accent float-end">Paid</span>
                    </div>
                    <div class="timeline-item">
                        <h6 class="fw-bold mb-1" style="color: var(--navy-dark);">Flutter Development</h6>
                        <small class="text-muted">Pembayaran terakhir: 10 Jan 2024</small>
                        <span class="badge badge-accent float-end">Paid</span>
                    </div>
                    <div class="timeline-item">
                        <h6 class="fw-bold mb-1" style="color: var(--navy-dark);">UI/UX Design</h6>
                        <small class="text-muted">Pembayaran berikutnya: 5 Feb 2024</small>
                        <span class="badge badge-navy float-end">Pending</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- NOTIFIKASI & AKTIVITAS -->
<div class="row g-4 mt-4">
    <div class="col-md-6">
        <div class="navy-card delay-3">
            <div class="navy-card-header">
                <h5 class="mb-0 fw-bold">
                    <i class="fa-solid fa-bell me-2"></i>
                    Notifikasi Terbaru
                </h5>
            </div>
            <div class="navy-card-body">
                <div class="d-flex align-items-start mb-4">
                    <div class="rounded-circle p-2 me-3" style="background: rgba(10, 25, 47, 0.1);">
                        <i class="fa-solid fa-calendar-check text-navy" style="color: var(--navy-dark);"></i>
                    </div>
                    <div>
                        <h6 class="fw-bold mb-1" style="color: var(--navy-dark);">Live Session Besok</h6>
                        <p class="text-muted mb-1">Advanced JavaScript Patterns - 19:00 WIB</p>
                        <small class="text-muted">2 jam yang lalu</small>
                    </div>
                </div>
                
                <div class="d-flex align-items-start mb-4">
                    <div class="rounded-circle p-2 me-3" style="background: rgba(100, 255, 218, 0.1);">
                        <i class="fa-solid fa-trophy text-accent" style="color: var(--accent-blue);"></i>
                    </div>
                    <div>
                        <h6 class="fw-bold mb-1" style="color: var(--navy-dark);">Pencapaian Baru!</h6>
                        <p class="text-muted mb-1">Anda mendapatkan badge "Code Master"</p>
                        <small class="text-muted">Kemarin, 14:30</small>
                    </div>
                </div>
                
                <div class="d-flex align-items-start">
                    <div class="rounded-circle p-2 me-3" style="background: rgba(10, 25, 47, 0.1);">
                        <i class="fa-solid fa-rotate text-navy" style="color: var(--navy-dark);"></i>
                    </div>
                    <div>
                        <h6 class="fw-bold mb-1" style="color: var(--navy-dark);">Spin Tersedia</h6>
                        <p class="text-muted mb-1">Spin harian Anda sudah bisa digunakan</p>
                        <small class="text-muted">2 hari yang lalu</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-6">
        <div class="navy-card delay-4">
            <div class="navy-card-header">
                <h5 class="mb-0 fw-bold">
                    <i class="fa-solid fa-chart-bar me-2"></i>
                    Progress Mingguan
                </h5>
            </div>
            <div class="navy-card-body">
                <div class="text-center mb-4">
                    <h2 class="fw-bold mb-0" style="color: var(--navy-dark);">+18%</h2>
                    <small class="text-muted">Peningkatan dari minggu lalu</small>
                </div>
                
                <div class="mb-3">
                    <div class="d-flex justify-content-between mb-1">
                        <small class="text-muted">Fullstack JavaScript</small>
                        <small class="fw-bold" style="color: var(--navy-dark);">+12%</small>
                    </div>
                    <div class="progress-navy">
                        <div class="progress-bar-navy" data-width="68"></div>
                    </div>
                </div>
                
                <div class="mb-3">
                    <div class="d-flex justify-content-between mb-1">
                        <small class="text-muted">Flutter Development</small>
                        <small class="fw-bold" style="color: var(--navy-dark);">+8%</small>
                    </div>
                    <div class="progress-navy">
                        <div class="progress-bar-navy" data-width="42"></div>
                    </div>
                </div>
                
                <div class="mb-3">
                    <div class="d-flex justify-content-between mb-1">
                        <small class="text-muted">UI/UX Design</small>
                        <small class="fw-bold" style="color: var(--navy-dark);">+15%</small>
                    </div>
                    <div class="progress-navy">
                        <div class="progress-bar-navy" data-width="25"></div>
                    </div>
                </div>
                
                <button class="btn btn-navy w-100 mt-3">
                    <i class="fa-solid fa-chart-line me-2"></i> Lihat Detail Progress
                </button>
            </div>
        </div>
    </div>
</div>

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
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Animate elements on scroll
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
        
        // Floating animation for header text
        const headerText = document.querySelector('.text-accent');
        let scale = 1;
        let direction = 1;
        
        function pulseText() {
            scale += 0.002 * direction;
            if (scale >= 1.02) direction = -1;
            if (scale <= 1) direction = 1;
            
            headerText.style.transform = `scale(${scale})`;
            requestAnimationFrame(pulseText);
        }
        
        pulseText();
    });
</script>

@endsection