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
    <div class="card-body p-0">
        <table class="table table-striped projects">
            <thead>
                <tr>
                    <th>Judul Kursus</th>
                    <th>Level</th>
                    <th>Harga</th>
                    <th>Status</th>
                    <th style="width: 20%">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($courses as $course)
                <tr>
                    <td>
                        <a>{{ $course->title }}</a>
                        <br/>
                        <small>Dibuat: {{ $course->created_at->format('d M Y') }}</small>
                    </td>
                    <td>
                        <span class="badge badge-{{ $course->level == 'pemula' ? 'success' : ($course->level == 'menengah' ? 'warning' : 'danger') }}">
                            {{ ucfirst($course->level) }}
                        </span>
                    </td>
                    <td>
                        {{ $course->price > 0 ? 'Rp ' . number_format($course->price, 0, ',', '.') : 'Gratis' }}
                    </td>
                    <td>
                        <span class="badge badge-{{ $course->is_premium ? 'secondary' : 'success' }}">
                            {{ $course->is_premium ? 'Premium' : 'Free' }}
                        </span>
                    </td>
                    <td class="project-actions">
                        <a class="btn btn-primary btn-sm" href="{{ route('courses.show', $course->id) }}">
                            <i class="fas fa-folder"></i> Kelola Materi
                        </a>
                        <a class="btn btn-info btn-sm" href="{{ route('courses.edit', $course->id) }}">
                            <i class="fas fa-pencil-alt"></i>
                        </a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@stop
