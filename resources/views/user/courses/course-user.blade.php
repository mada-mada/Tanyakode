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
    
    .progress-custom { height: 6px; background-color: #E2E8F0; border-radius: 4px; }
    .progress-bar-custom { background-color: #5FF2D6; border-radius: 4px; }
    
    /* Tombol Filter */
    .filter-btn { border: 1px solid #e2e8f0; color: #64748b; padding: 6px 16px; border-radius: 20px; font-size: 14px; font-weight: 600; background: white; transition: all 0.2s; }
    .filter-btn:hover, .filter-btn.active { background-color: #0B132B; color: white; border-color: #0B132B; }
</style>

<div class="container-fluid p-4">
    
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
        <div>
            <h2 class="fw-bold text-dark" style="font-family: sans-serif;">Kursus Saya</h2>
            <p class="text-muted">Lanjutkan progres belajar Anda hari ini!</p>
        </div>
        
        <div class="search-wrapper" style="width: 300px;">
            <div class="input-group">
                <input type="text" class="form-control border-0 shadow-sm" placeholder="Cari kursus..." style="padding: 10px 20px;">
                <button class="btn btn-dark" type="button" style="background-color: #0B132B;">
                    <i class="fas fa-search"></i>
                </button>
            </div>
        </div>
    </div>

    <div class="d-flex gap-2 mb-5 overflow-auto pb-2">
        <button class="filter-btn active">Semua</button>
        <button class="filter-btn">Dasar (Beginner)</button>
        <button class="filter-btn">Menengah (Intermediate)</button>
        <button class="filter-btn">Tinggi (Expert)</button>
    </div>

    <div class="row">
        @php
            // 1. BUAT DUMMY COURSE (LEVEL TINGGI/EXPERT)
            $dummyExpert = new \stdClass();
            $dummyExpert->title = 'Cyber Security Advanced';
            $dummyExpert->slug = 'cyber-security-advanced';
            $dummyExpert->level = 'expert';
            $dummyExpert->description = 'Pelajari teknik penetrasi testing, keamanan jaringan, dan kriptografi tingkat lanjut.';
            $dummyExpert->thumbnail_url = null;
            $dummyExpert->price = 0; // Tambahkan ini biar ga error kalau dipanggil

            // 2. SOLUSI ERROR "stdClass::getKey()"
            // Kita ubah dulu data dari database ke Array biasa, baru digabung.
            // Cara ini aman karena kita tidak memaksa data dummy jadi Model.
            $allCourses = $courses->all(); 
            $allCourses[] = $dummyExpert; 
        @endphp

        @forelse($allCourses as $course)
            @php
                // --- LOGIKA INISIAL (CS, WC, BT) ---
                $words = explode(' ', $course->title);
                $initials = '';
                foreach($words as $index => $word) {
                    if($index < 2) $initials .= strtoupper(substr($word, 0, 1));
                }

                // --- LOGIKA WARNA BADGE ---
                $levelConfig = match(strtolower($course->level)) {
                    'pemula' => ['label' => 'Beginner', 'class' => 'badge-beginner'],
                    'menengah' => ['label' => 'Intermediate', 'class' => 'badge-intermediate'],
                    'expert' => ['label' => 'Advanced', 'class' => 'badge-expert'], // Warna Ungu
                    default => ['label' => 'General', 'class' => 'badge-cyan']
                };
                
                $progress = 0; 
            @endphp

            <div class="col-12 col-md-6 col-lg-4 mb-4">
                <div class="card card-custom h-100">
                    
                    <div class="bg-dark-theme p-5 position-relative text-center d-flex justify-content-center align-items-center" style="height: 220px;">
                        
                        <span class="badge {{ $levelConfig['class'] }} position-absolute" style="top: 20px; right: 20px; padding: 8px 16px; border-radius: 20px;">
                            {{ $levelConfig['label'] }}
                        </span>

                        <h1 class="text-cyan-theme fw-bold m-0" style="font-size: 8rem; letter-spacing: -5px; line-height: 1;">
                            {{ $initials }}
                        </h1>
                    </div>

                    <div class="card-body p-4 d-flex flex-column">
                        <h5 class="fw-bold text-dark mb-2">{{ $course->title }}</h5>
                        
                        <p class="text-muted small mb-4" style="line-height: 1.5;">
                            {{ Str::limit($course->description ?? 'Pelajari materi ' . $course->title . ' dari dasar hingga mahir dengan studi kasus nyata.', 80) }}
                        </p>

                        <div class="mt-auto">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="fw-bold small text-dark">Progress</span>
                                <span class="fw-bold small text-success">{{ $progress }}%</span>
                            </div>
                            
                            <div class="progress progress-custom mb-4">
                                <div class="progress-bar progress-bar-custom" role="progressbar" style="width: {{ $progress }}%"></div>
                            </div>

                            @if($course->level == 'expert')
                                {{-- Link Dummy untuk Expert --}}
                                <a href="#" onclick="alert('Ini hanya contoh tampilan level Tinggi.')" class="btn btn-start w-100 py-3">
                                    Mulai Belajar <i class="fas fa-play ms-2"></i>
                                </a>
                            @else
                                {{-- Link Asli untuk Course DB --}}
                                <a href="{{ route('user.courses.show', $course->slug) }}" class="btn btn-start w-100 py-3">
                                    Lihat Detail <i class="fas fa-arrow-right ms-2"></i>
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

        @empty
            <div class="col-12">
                <div class="alert alert-light shadow-sm border-0" role="alert">
                    <strong>Belum ada kursus.</strong>
                </div>
            </div>
        @endforelse
    </div>
</div>
@endsection