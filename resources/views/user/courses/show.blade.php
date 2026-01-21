@extends('layouts.user')

@section('content')

<style>
    @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');
    body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #f8fafc; }
    .hero-section {
        background: radial-gradient(circle at 10% 20%, #1e293b 0%, #0f172a 90%);
        color: white;
        padding: 60px 0 80px 0;
        position: relative;
        overflow: hidden;
    }
    .hero-bg-pattern {
        position: absolute;
        top: 0; left: 0; width: 100%; height: 100%;
        background-image: linear-gradient(#334155 1px, transparent 1px), linear-gradient(90deg, #334155 1px, transparent 1px);
        background-size: 40px 40px;
        opacity: 0.05;
    }
    .overlap-card {
        border: none; border-radius: 16px;
        box-shadow: 0 20px 40px -15px rgba(0,0,0,0.1);
        background: white; transition: transform 0.3s ease;
        position: relative; z-index: 10;
    }
    @media (min-width: 992px) { .hero-section { padding-bottom: 120px; } .overlap-card { margin-top: -80px; } }
    @media (max-width: 991.98px) { .hero-section { padding-bottom: 40px; } .overlap-card { margin-top: 20px; } }
    .btn-primary-custom {
        background: linear-gradient(135deg, #06b6d4 0%, #0ea5e9 100%);
        border: none; color: white; font-weight: 700; padding: 14px 20px; border-radius: 12px;
        transition: all 0.3s; box-shadow: 0 4px 6px -1px rgba(6, 182, 212, 0.3);
    }
    .btn-primary-custom:hover { transform: translateY(-2px); box-shadow: 0 10px 15px -3px rgba(6, 182, 212, 0.4); color: white; }
    .accordion-item { border: 1px solid #f1f5f9; margin-bottom: 10px; border-radius: 12px !important; overflow: hidden; box-shadow: 0 2px 4px rgba(0,0,0,0.02); }
    .accordion-button { background-color: white; font-weight: 600; color: #1e293b; padding: 16px 20px; box-shadow: none !important; }
    .accordion-button:not(.collapsed) { background-color: #f0f9ff; color: #0284c7; }
    .thumbnail-placeholder {
        height: 200px; width: 100%; background: linear-gradient(45deg, #1e293b, #334155);
        display: flex; align-items: center; justify-content: center; color: rgba(255,255,255,0.2); font-size: 3rem;
    }
    .sticky-sidebar { position: sticky; top: 100px; }
</style>

{{-- HITUNG TOTAL MATERI (Versi Aman) --}}
@php
    $totalContent = 0;
    if($course->modules) {
        foreach($course->modules as $mod) {
            // Menggunakan nama 'contents' dan fungsi count() yang aman
            $totalContent += $mod->contents ? $mod->contents->count() : 0;
        }
    }
@endphp

<div class="hero-section">
    <div class="hero-bg-pattern"></div>
    <div class="container position-relative">
        <div class="row">
            <div class="col-lg-8">
                <div class="mb-3">
                    <span class="badge bg-white bg-opacity-10 border border-white border-opacity-20 text-white px-3 py-2 rounded-pill fw-bold" 
                          style="backdrop-filter: blur(4px); font-size: 0.75rem; letter-spacing: 0.5px;">
                        <i class="fas fa-layer-group me-2 text-info"></i> {{ strtoupper($course->level ?? 'GENERAL') }}
                    </span>
                </div>
                <h1 class="display-5 fw-bold mb-3 lh-sm">{{ $course->title }}</h1>
                <p class="text-white text-opacity-75 mb-4 lead fs-6" style="max-width: 600px; font-weight: 400; line-height: 1.7;">
                    {{ Str::limit($course->description, 150) }}
                </p>
                <div class="d-flex flex-wrap gap-4 text-sm text-white text-opacity-90 fw-medium">
                    <span class="d-flex align-items-center"><i class="fas fa-book-open me-2 text-info"></i> {{ $course->modules ? $course->modules->count() : 0 }} Modul</span>
                    <span class="d-flex align-items-center"><i class="fas fa-film me-2 text-warning"></i> {{ $totalContent }} Materi</span>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="container pb-5">
    <div class="row">
        <div class="col-lg-8">
            <div class="d-flex gap-2 mb-4 mt-lg-0 mt-4 overflow-auto pb-2">
                <button class="btn btn-dark rounded-pill px-4 fw-bold shadow-sm">Tentang Kursus</button>
                <button class="btn btn-white border rounded-pill px-4 fw-bold text-muted hover-bg-light">Ulasan</button>
            </div>

            <div class="mb-5">
                <h4 class="fw-bold text-dark mb-3">Tentang Kursus Ini</h4>
                <div class="card border-0 shadow-sm bg-white p-4 mb-3" style="border-radius: 16px;">
                    <p class="text-muted mb-0" style="line-height: 1.8; text-align: justify;">
                        {{ $course->description }}
                    </p>
                </div>
            </div>

            <div class="mb-5">
                <div class="d-flex justify-content-between align-items-end mb-3">
                    <h4 class="fw-bold text-dark mb-0">Kurikulum</h4>
                    <span class="text-muted small fw-bold">{{ $course->modules ? $course->modules->count() : 0 }} Modul • {{ $totalContent }} Materi</span>
                </div>

                <div class="accordion" id="accordionSyllabus">
                    @forelse($course->modules as $index => $module)
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="heading{{ $module->id }}">
                            <button class="accordion-button {{ $index == 0 ? '' : 'collapsed' }}" type="button" data-bs-toggle="collapse" data-bs-target="#collapse{{ $module->id }}">
                                <div class="d-flex justify-content-between w-100 me-3 align-items-center">
                                    <span class="fw-bold">Modul {{ $index + 1 }}: {{ $module->title }}</span>
                                    <small class="text-muted fw-normal bg-light px-2 py-1 rounded border">
                                        {{-- Menggunakan 'contents' --}}
                                        {{ $module->contents ? $module->contents->count() : 0 }} Materi
                                    </small>
                                </div>
                            </button>
                        </h2>
                        <div id="collapse{{ $module->id }}" class="accordion-collapse collapse {{ $index == 0 ? 'show' : '' }}" data-bs-parent="#accordionSyllabus">
                            <div class="accordion-body bg-white pt-2">
                                <ul class="list-unstyled mb-0">
                                    {{-- Loop contents --}}
                                    @forelse($module->contents as $content)
                                        <li class="d-flex align-items-center py-2 border-bottom border-light last:border-0">
                                            @if(Str::contains(strtolower($content->content_type ?? 'video'), 'video'))
                                                <div class="bg-light rounded-circle p-2 me-3 text-primary"><i class="fas fa-play fa-sm"></i></div>
                                            @else
                                                <div class="bg-light rounded-circle p-2 me-3 text-warning"><i class="fas fa-file-alt fa-sm"></i></div>
                                            @endif
                                            <div>
                                                <span class="d-block text-dark fw-medium small">{{ $content->title }}</span>
                                            </div>
                                        </li>
                                    @empty
                                        <li class="text-muted small py-2">Belum ada materi di modul ini.</li>
                                    @endforelse
                                </ul>
                            </div>
                        </div>
                    </div>
                    @empty
                        <div class="alert alert-info">Belum ada kurikulum yang disusun.</div>
                    @endforelse
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="overlap-card sticky-sidebar p-1">
                <div class="p-3">
                    <div class="rounded-3 overflow-hidden position-relative mb-3 shadow-sm border">
                        @if(!empty($course->cover) && file_exists(storage_path('app/public/' . $course->cover)))
                            <img src="{{ asset('storage/' . $course->cover) }}" class="img-fluid w-100" style="object-fit: cover; height: 200px;" alt="{{ $course->title }}">
                        @else
                            <div class="thumbnail-placeholder"><i class="fas fa-laptop-code"></i></div>
                        @endif
                        <div class="position-absolute top-0 start-0 w-100 h-100 d-flex align-items-center justify-content-center bg-dark bg-opacity-10">
                            <div class="bg-white rounded-circle p-3 shadow-lg text-primary"><i class="fas fa-play"></i></div>
                        </div>
                    </div>

                    <div class="text-center mb-4">
                        @if($course->price == 0)
                            <h2 class="fw-bold text-success mb-0">GRATIS</h2>
                        @else
                            <h2 class="fw-bold text-dark mb-0">Rp {{ number_format($course->price, 0, ',', '.') }}</h2>
                        @endif
                    </div>
                    
                    <div class="d-grid gap-2 mb-4">
                        <a href="{{ route('user.courses.learning', ['slug' => $course->id]) }}" class="btn-primary-custom text-center text-decoration-none">
                            Mulai Belajar Sekarang <i class="fas fa-arrow-right ms-2"></i>
                        </a>
                    </div>

                    <div class="bg-light p-3 rounded-3">
                        <h6 class="fw-bold mb-3 small text-uppercase text-muted">Termasuk dalam kursus:</h6>
                        <ul class="list-unstyled d-flex flex-column gap-2 text-sm text-dark mb-0">
                            <li class="d-flex align-items-center text-muted small"><i class="fas fa-clock text-success me-2"></i> Akses Selamanya</li>
                            <li class="d-flex align-items-center text-muted small"><i class="fas fa-mobile-alt text-success me-2"></i> Akses di HP & PC</li>
                            <li class="d-flex align-items-center text-muted small"><i class="fas fa-certificate text-success me-2"></i> Sertifikat Penyelesaian</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection