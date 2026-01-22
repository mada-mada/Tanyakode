@extends('layouts.user')

@section('content')

<style>
    .bg-dark-theme { background-color: #0B132B; }
    .text-cyan-theme { color: #5FF2D6; }
    .badge-cyan { background-color: #5FF2D6; color: #0B132B; font-weight: bold; }

    .badge-beginner { background-color: #5FF2D6; color: #0B132B; }
    .badge-intermediate { background-color: #FCA5A5; color: #450a0a; }
    .badge-expert { background-color: #c084fc; color: #3b0764; }

    .btn-start { background-color: #0B132B; color: white; border-radius: 8px; font-weight: 600; transition: all 0.3s; }
    .btn-start:hover { background-color: #1C2541; color: white; transform: translateY(-2px); }
    .card-custom { border: none; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 6px rgba(0,0,0,0.05); transition: transform 0.3s ease; }
    .card-custom:hover { transform: translateY(-5px); box-shadow: 0 10px 15px rgba(0,0,0,0.1); }

    .filter-btn { border: 1px solid #e2e8f0; color: #64748b; padding: 6px 16px; border-radius: 20px; font-size: 14px; font-weight: 600; background: white; transition: all 0.2s; text-decoration: none; display: inline-block; }
    .filter-btn:hover { background-color: #f1f5f9; color: #0B132B; text-decoration: none; }
    .filter-btn.active { background-color: #0B132B; color: white; border-color: #0B132B; }
</style>

<div class="container-fluid p-4">

    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
        <div>
            <h2 class="fw-bold text-dark">Katalog Kursus</h2>
            <p class="text-muted">Jelajahi semua kursus yang tersedia untuk meningkatkan skill Anda</p>
        </div>

        <form action="{{ route('user.courses.catalog') }}" method="GET" class="search-wrapper" style="width: 300px;">
            @if(request('level'))
                <input type="hidden" name="level" value="{{ request('level') }}">
            @endif
            
            <div class="input-group">
                <input type="text" name="search" class="form-control border-0 shadow-sm" placeholder="Cari kursus..." value="{{ request('search') }}">
                <button class="btn btn-dark" type="submit" style="background-color: #0B132B;">
                    <i class="fas fa-search"></i>
                </button>
            </div>
        </form>
    </div>

    <div class="d-flex gap-2 mb-5 overflow-auto pb-2">
        <a href="{{ route('user.courses.catalog') }}" class="filter-btn {{ !request('level') || request('level') == 'Semua' ? 'active' : '' }}">Semua</a>
        <a href="{{ route('user.courses.catalog', ['level' => 'Pemula', 'search' => request('search')]) }}" class="filter-btn {{ request('level') == 'Pemula' ? 'active' : '' }}">Beginner</a>
        <a href="{{ route('user.courses.catalog', ['level' => 'Menengah', 'search' => request('search')]) }}" class="filter-btn {{ request('level') == 'Menengah' ? 'active' : '' }}">Intermediate</a>
        <a href="{{ route('user.courses.catalog', ['level' => 'Lanjutan', 'search' => request('search')]) }}" class="filter-btn {{ request('level') == 'Lanjutan' ? 'active' : '' }}">Expert</a>
    </div>

    <div class="row">
        @forelse($courses as $course)
            @php
                // GENERATE INISIAL DARI NAMA COURSE (Misal: "Web Design" -> "WD")
                // Menggunakan 'name' sesuai asumsi tabel DB, ubah ke 'title' jika kolom di DB anda 'title'
                $courseName = $course->name ?? $course->title; 
                $words = explode(' ', $courseName);
                $initials = '';
                foreach($words as $i => $word) {
                    if($i < 2) $initials .= strtoupper(substr($word, 0, 1));
                }

                // CONFIG WARNA BADGE BERDASARKAN LEVEL
                $levelConfig = match(strtolower($course->level ?? 'general')) {
                    'pemula', 'beginner' => ['label' => 'Beginner', 'class' => 'badge-beginner'],
                    'menengah', 'intermediate' => ['label' => 'Intermediate', 'class' => 'badge-intermediate'],
                    'lanjutan', 'expert', 'advanced' => ['label' => 'Expert', 'class' => 'badge-expert'],
                    default => ['label' => ucfirst($course->level ?? 'General'), 'class' => 'badge-cyan']
                };
            @endphp

            <div class="col-12 col-md-6 col-lg-4 mb-4">
                <div class="card card-custom h-100">

                    <div class="bg-dark-theme p-5 position-relative text-center d-flex justify-content-center align-items-center" style="height: 220px; overflow: hidden;">
                        
                        <span class="badge {{ $levelConfig['class'] }} position-absolute shadow-sm" style="top: 20px; right: 20px; padding: 8px 16px; border-radius: 20px; z-index: 10;">
                            {{ $levelConfig['label'] }}
                        </span>

                        @if(!empty($course->thumbnail) && file_exists(public_path('storage/'.$course->thumbnail)))
                            <img src="{{ asset('storage/'.$course->thumbnail) }}" alt="{{ $courseName }}" class="w-100 h-100 position-absolute top-0 start-0" style="object-fit: cover; opacity: 0.8;">
                        @else
                            <h1 class="text-cyan-theme fw-bold m-0 position-relative" style="font-size: 6rem; z-index: 5; opacity: 0.5;">
                                {{ $initials }}
                            </h1>
                        @endif
                    </div>

                    <div class="card-body p-4 d-flex flex-column">
                        <h5 class="fw-bold text-dark mb-2">{{ $courseName }}</h5>

                        <p class="text-muted small mb-3">
                            {{ Str::limit($course->description ?? 'Pelajari materi ' . $courseName . ' secara mendalam di sini.', 90) }}
                        </p>

                        <div class="mb-4">
                            @if($course->price == 0)
                                <span class="badge bg-success text-white">GRATIS</span>
                            @else
                                <h6 class="fw-bold text-primary mb-0">Rp {{ number_format($course->price, 0, ',', '.') }}</h6>
                            @endif
                        </div>

                        <div class="mt-auto">
                            <a href="{{ route('user.courses.show', $course->slug) }}" class="btn btn-start w-100 py-3">
                                Lihat Detail <i class="fas fa-arrow-right ms-2"></i>
                            </a>
                        </div>
                    </div>

                </div>
            </div>

        @empty
            <div class="col-12">
                <div class="alert alert-light shadow-sm border-0 text-center py-5">
                    <div class="mb-3">
                        <i class="fas fa-search fa-3x text-muted opacity-50"></i>
                    </div>
                    <h5 class="text-muted fw-bold">Tidak ada kursus ditemukan.</h5>
                    <p class="text-muted small">Coba ubah kata kunci pencarian atau filter level Anda.</p>
                    <a href="{{ route('user.courses.catalog') }}" class="btn btn-outline-secondary btn-sm mt-2">Reset Filter</a>
                </div>
            </div>
        @endforelse
    </div>

    <div class="d-flex justify-content-center mt-4">
        {{ $courses->links() }}
    </div>

</div>
@endsection