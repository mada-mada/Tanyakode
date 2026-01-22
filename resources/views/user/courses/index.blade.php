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
    .card-custom { border: none; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 6px rgba(0,0,0,0.05); transition: transform 0.2s; }
    .card-custom:hover { transform: translateY(-5px); }
    
    /* Tombol Filter */
    .filter-btn { border: 1px solid #e2e8f0; color: #64748b; padding: 6px 16px; border-radius: 20px; font-size: 14px; font-weight: 600; background: white; transition: all 0.2s; }
    .filter-btn:hover, .filter-btn.active { background-color: #0B132B; color: white; border-color: #0B132B; }
</style>

<div class="container-fluid p-4">
    
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
        <div>
            <h2 class="fw-bold text-dark" style="font-family: sans-serif;">Kursus</h2>
            <p class="text-muted">Ikuti kursus yang tersedia untuk meningkatkan kemampuan Anda.</p>
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
        <button class="filter-btn">Dasar (Beginner)</button>
        <button class="filter-btn">Menengah (Intermediate)</button>
        <button class="filter-btn">Tinggi (Expert)</button>
    </div>

    <div class="row">
        @forelse($courses as $course)
            @php
                // --- LOGIKA WARNA BADGE ---
                $levelConfig = match(strtolower($course->level)) {
                    'pemula', 'beginner' => ['label' => 'Beginner', 'class' => 'badge-beginner'],
                    'menengah', 'intermediate' => ['label' => 'Intermediate', 'class' => 'badge-intermediate'],
                    'expert', 'mahir', 'lanjutan' => ['label' => 'Expert', 'class' => 'badge-expert'], 
                    default => ['label' => ucfirst($course->level), 'class' => 'badge-cyan']
                };
            @endphp

            <div class="col-12 col-md-6 col-lg-4 mb-4">
                <div class="card card-custom h-100">
                    
                    {{-- HEADER KARTU: Gambar atau Inisial --}}
                    <div class="position-relative text-center d-flex justify-content-center align-items-center overflow-hidden bg-dark-theme" style="height: 220px;">
                        
                        {{-- Badge Level (Absolute) --}}
                        <span class="badge {{ $levelConfig['class'] }} position-absolute" style="top: 20px; right: 20px; padding: 8px 16px; border-radius: 20px; z-index: 10;">
                            {{ $levelConfig['label'] }}
                        </span>

                        @if($course->thumbnail_url)
                            {{-- TAMPILKAN GAMBAR JIKA ADA --}}
                            <img src="{{ asset('storage/' . $course->thumbnail_url) }}" alt="{{ $course->title }}" class="w-100 h-100" style="object-fit: cover;">
                        @else
                            {{-- TAMPILKAN INISIAL JIKA TIDAK ADA GAMBAR --}}
                            @php
                                $words = explode(' ', $course->title);
                                $initials = '';
                                foreach($words as $index => $word) {
                                    if($index < 2) $initials .= strtoupper(substr($word, 0, 1));
                                }
                            @endphp
                            <h1 class="text-cyan-theme fw-bold m-0" style="font-size: 8rem; letter-spacing: -5px; line-height: 1;">
                                {{ $initials }}
                            </h1>
                        @endif
                    </div>

                    {{-- BODY KARTU --}}
                    <div class="card-body p-4 d-flex flex-column">
                        <h5 class="fw-bold text-dark mb-2">{{ $course->title }}</h5>
                        
                        <p class="text-muted small mb-4" style="line-height: 1.5;">
                            {{ Str::limit($course->description, 100) }}
                        </p>

                        <div class="mt-auto">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <span class="fw-bold text-dark">Harga</span>
                                <span class="fw-bold text-success" style="font-size: 1.1rem;">
                                    {{ $course->price > 0 ? 'Rp ' . number_format($course->price, 0, ',', '.') : 'Gratis' }}
                                </span>
                            </div>
                            
                            <a href="{{ route('user.courses.show', $course->slug) }}" class="btn btn-start w-100 py-3">
                                Lihat Detail <i class="fas fa-arrow-right ms-2"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

        @empty
            <div class="col-12">
                <div class="alert alert-light shadow-sm border-0 text-center py-5" role="alert">
                    <i class="fas fa-box-open fa-3x text-muted mb-3"></i>
                    <h5 class="text-muted">Belum ada kursus yang tersedia saat ini.</h5>
                </div>
            </div>
        @endforelse
    </div>
</div>
@endsection