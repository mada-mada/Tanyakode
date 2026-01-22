@extends('layouts.user')

@section('content')

{{-- Style Khusus --}}
<style>
    .bg-dark-theme { background-color: #0B132B; }
    .text-cyan-theme { color: #5FF2D6; }
    .badge-cyan { background-color: #5FF2D6; color: #0B132B; font-weight: bold; }
    
    /* Warna Badge Berbeda Tiap Level */
    .badge-beginner { background-color: #5FF2D6; color: #0B132B; }       /* Tosca */
    .badge-intermediate { background-color: #FCA5A5; color: #450a0a; }  /* Merah Muda */
    .badge-expert { background-color: #c084fc; color: #3b0764; }        /* Ungu */

    .btn-start { background-color: #0B132B; color: white; border-radius: 8px; font-weight: 600; }
    .btn-start:hover { background-color: #1C2541; color: white; }
    .card-custom { border: none; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 6px rgba(0,0,0,0.05); }
    
    /* Tombol Filter */
    .filter-btn { border: 1px solid #e2e8f0; color: #64748b; padding: 6px 16px; border-radius: 20px; font-size: 14px; font-weight: 600; background: white; transition: all 0.2s; }
    .filter-btn:hover, .filter-btn.active { background-color: #0B132B; color: white; border-color: #0B132B; }
    
    /* Progress Bar Styling */
    .progress-container {
        background-color: #e2e8f0;
        border-radius: 10px;
        overflow: hidden;
        height: 8px;
        margin: 10px 0;
    }
    
    .progress-fill {
        height: 100%;
        background: linear-gradient(90deg, #4361ee, #3a0ca3);
        border-radius: 10px;
        transition: width 0.3s ease;
    }
    
    .course-status {
        font-size: 0.8rem;
        font-weight: 600;
        padding: 3px 10px;
        border-radius: 12px;
    }
    
    .status-enrolled { background-color: #dbeafe; color: #1e40af; }
    .status-completed { background-color: #dcfce7; color: #166534; }
    .status-not-enrolled { background-color: #fef3c7; color: #92400e; }
</style>

<div class="container-fluid p-4">
    
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
        <div>
            <h2 class="fw-bold text-dark" style="font-family: sans-serif;">Kursus Saya</h2>
            <p class="text-muted">Kursus yang Anda ikuti dan progress pembelajaran.</p>
        </div>
        
        <div class="search-wrapper" style="width: 300px;">
            <form action="{{ route('user.courses.index') }}" method="GET">
                <div class="input-group">
                    <input type="text" name="search" class="form-control border-0 shadow-sm" placeholder="Cari kursus..." value="{{ request('search') }}" style="padding: 10px 20px;">
                    <button class="btn btn-dark" type="submit" style="background-color: #0B132B;">
                        <i class="fas fa-search"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div class="d-flex gap-2 mb-5 overflow-auto pb-2">
        <button class="filter-btn active">Semua</button>
        <button class="filter-btn">Sedang Dikerjakan</button>
        <button class="filter-btn">Selesai</button>
        <button class="filter-btn">Belum Dimulai</button>
    </div>

    @if($courses->count() > 0)
        <div class="row">
            @foreach($courses as $enrollment)
                @php
                    $course = (object) $enrollment; // Convert array to object
                    
                    // Inisial dari judul
                    $words = explode(' ', $course->title);
                    $initials = '';
                    foreach($words as $index => $word) {
                        if($index < 2) $initials .= strtoupper(substr($word, 0, 1));
                    }

                    // Level configuration
                    $levelConfig = match(strtolower($course->level)) {
                        'pemula', 'beginner' => ['label' => 'Beginner', 'class' => 'badge-beginner'],
                        'menengah', 'intermediate' => ['label' => 'Intermediate', 'class' => 'badge-intermediate'],
                        'expert', 'mahir', 'lanjutan' => ['label' => 'Expert', 'class' => 'badge-expert'], 
                        default => ['label' => ucfirst($course->level), 'class' => 'badge-cyan']
                    };

                    // Progress percentage
                    $progress = $course->progress_percentage ?? 0;
                    $status = $course->status ?? 'enrolled';
                    
                    // Status configuration
                    $statusConfig = [
                        'enrolled' => ['label' => 'Sedang Dikerjakan', 'class' => 'status-enrolled'],
                        'completed' => ['label' => 'Selesai', 'class' => 'status-completed'],
                        'not_started' => ['label' => 'Belum Dimulai', 'class' => 'status-not-enrolled']
                    ];
                    
                    $statusInfo = $statusConfig[$status] ?? $statusConfig['enrolled'];
                @endphp

                <div class="col-12 col-md-6 col-lg-4 mb-4">
                    <div class="card card-custom h-100">
                        
                        <div class="bg-dark-theme p-5 position-relative text-center d-flex justify-content-center align-items-center" style="height: 220px;">
                            
                            <span class="badge {{ $levelConfig['class'] }} position-absolute" style="top: 20px; right: 20px; padding: 8px 16px; border-radius: 20px;">
                                {{ $levelConfig['label'] }}
                            </span>

                            <span class="course-status {{ $statusInfo['class'] }} position-absolute" style="top: 20px; left: 20px;">
                                {{ $statusInfo['label'] }}
                            </span>

                            <h1 class="text-cyan-theme fw-bold m-0" style="font-size: 8rem; letter-spacing: -5px; line-height: 1;">
                                {{ $initials }}
                            </h1>
                        </div>

                        <div class="card-body p-4 d-flex flex-column">
                            <h5 class="fw-bold text-dark mb-2">{{ $course->title }}</h5>
                            
                            <div class="progress-container">
                                <div class="progress-fill" style="width: {{ $progress }}%"></div>
                            </div>
                            
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <span class="text-muted small">Progress</span>
                                <span class="fw-bold text-primary">{{ round($progress, 1) }}%</span>
                            </div>

                            <div class="mt-auto">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <span class="fw-bold text-dark">Harga</span>
                                    <span class="fw-bold {{ $course->price == 0 ? 'text-success' : 'text-dark' }}" style="font-size: 1.1rem;">
                                        {{ $course->price == 0 ? 'Gratis' : 'Rp ' . number_format($course->price, 0, ',', '.') }}
                                    </span>
                                </div>
                                
                                @if($status == 'completed')
                                    <a href="{{ route('user.courses.learning', $course->slug) }}" class="btn btn-success w-100 py-3">
                                        <i class="fas fa-certificate me-2"></i> Lihat Sertifikat
                                    </a>
                                @else
                                    <a href="{{ route('user.courses.learning', $course->slug) }}" class="btn btn-start w-100 py-3">
                                        Lanjutkan Belajar <i class="fas fa-arrow-right ms-2"></i>
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="col-12">
            <div class="card card-custom">
                <div class="card-body text-center py-5">
                    <div class="mb-4">
                        <i class="fas fa-book-open fa-4x text-muted"></i>
                    </div>
                    <h4 class="text-dark mb-3">Belum Ada Kursus</h4>
                    <p class="text-muted mb-4">Anda belum mengikuti kursus apapun. Mulai perjalanan belajar Anda dengan memilih kursus yang tersedia.</p>
                    <a href="{{ route('courses.all') }}" class="btn btn-start px-4">
                        <i class="fas fa-search me-2"></i> Jelajahi Kursus
                    </a>
                </div>
            </div>
        </div>
    @endif
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const filterButtons = document.querySelectorAll('.filter-btn');
        
        filterButtons.forEach(button => {
            button.addEventListener('click', function() {
                filterButtons.forEach(btn => btn.classList.remove('active'));
                this.classList.add('active');
                
                const filterType = this.textContent.trim();
                filterCourses(filterType);
            });
        });
        
        function filterCourses(type) {
            const cards = document.querySelectorAll('.col-12.col-md-6.col-lg-4');
            
            cards.forEach(card => {
                const statusElement = card.querySelector('.course-status');
                const status = statusElement ? statusElement.textContent.trim() : '';
                
                let shouldShow = true;
                
                switch(type) {
                    case 'Sedang Dikerjakan':
                        shouldShow = status === 'Sedang Dikerjakan';
                        break;
                    case 'Selesai':
                        shouldShow = status === 'Selesai';
                        break;
                    case 'Belum Dimulai':
                        shouldShow = status === 'Belum Dimulai';
                        break;
                }
                
                card.style.display = shouldShow ? 'block' : 'none';
            });
        }
    });
</script>
@endsection