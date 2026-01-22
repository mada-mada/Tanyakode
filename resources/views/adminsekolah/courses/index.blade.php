@extends('layouts.admin_course')

@section('title', 'Kursus Saya')

@section('content')
<style>
    /* Style tetap sama seperti yang Anda berikan */
    .bg-dark-theme { background-color: #0B132B; }
    .text-cyan-theme { color: #5FF2D6; }
    .badge-cyan { background-color: #5FF2D6; color: #0B132B; font-weight: bold; }
    
    /* Warna Badge Level */
    .badge-beginner { background-color: #dcfce7; color: #166534; }
    .badge-intermediate { background-color: #fef3c7; color: #92400e; }
    .badge-expert { background-color: #fee2e2; color: #991b1b; }

    .btn-create-custom {
        background-color: #5FF2D6; 
        color: #0B132B; 
        font-weight: 700;
        border: none;
        transition: transform 0.2s;
    }
    .btn-create-custom:hover { background-color: #4cebc0; transform: translateY(-2px); color: #0B132B; }

    .table-img { width: 100px; height: 60px; object-fit: cover; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
    .img-placeholder { width: 100px; height: 60px; background-color: #e2e8f0; border-radius: 8px; display: flex; align-items: center; justify-content: center; color: #94a3b8; }
</style>

<div class="container-fluid p-4">
    
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
        <div>
            <h2 class="fw-bold text-dark">Kursus Saya</h2>
            <p class="text-muted">Kelola kurikulum dan materi kursus sekolah Anda.</p>
        </div>
        
        <div class="d-flex align-items-center">
            <div class="search-wrapper" style="width: 300px;">
                {{-- PERBAIKAN: Route diarahkan ke courses.index --}}
                <form action="{{ route('courses.index') }}" method="GET">
                    <div class="input-group">
                        <input type="text" name="search" class="form-control border-0 shadow-sm" placeholder="Cari judul kursus..." value="{{ request('search') }}" style="padding: 10px 20px;">
                        <button class="btn btn-dark" type="submit" style="background-color: #0B132B;">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </form>
            </div>

            <a href="{{ route('courses.create') }}" class="btn btn-create-custom shadow-sm d-flex align-items-center" style="height: 44px; padding: 0 20px; margin-left: 20px;">
                <i class="fas fa-plus-circle me-2"></i> <span>Tambah Kursus</span>
            </a>
        </div>
    </div>

    <div class="card shadow-sm border-0" style="border-radius: 12px;">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="ps-4" style="width: 120px;">Thumbnail</th>
                            <th>Informasi Kursus</th>
                            <th>Level</th>
                            <th>Harga</th>
                            <th>Akses</th>
                            <th class="text-end pe-4">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($courses as $course)
                        <tr>
                            <td class="ps-4">
                                @if($course->thumbnail_url)
                                    <img src="{{ Storage::url($course->thumbnail_url) }}" alt="{{ $course->title }}" class="table-img">
                                @else
                                    <div class="img-placeholder"><i class="fas fa-image fa-lg"></i></div>
                                @endif
                            </td>
                            <td>
                                <span class="fw-bold text-dark">{{ $course->title }}</span><br/>
                                <small class="text-muted"><i class="far fa-calendar-alt me-1"></i> {{ $course->created_at->format('d M Y') }}</small>
                            </td>
                            <td>
                                @php
                                    $levelClass = $course->level == 'pemula' ? 'beginner' : ($course->level == 'menengah' ? 'intermediate' : 'expert');
                                @endphp
                                <span class="badge badge-{{ $levelClass }} px-3 py-2">
                                    {{ ucfirst($course->level) }}
                                </span>
                            </td>
                            <td>
                                <span class="fw-bold">{{ $course->price > 0 ? 'Rp ' . number_format($course->price, 0, ',', '.') : 'Gratis' }}</span>
                            </td>
                            <td>
                                <span class="badge {{ $course->is_premium ? 'bg-secondary' : 'bg-success' }}">
                                    {{ $course->is_premium ? 'Premium' : 'Free' }}
                                </span>
                            </td>
                            <td class="text-end pe-4">
                                <div class="btn-group">
                                    <a class="btn btn-primary btn-sm" href="{{ route('courses.show', $course->id) }}" title="Kelola Modul">
                                        <i class="fas fa-folder-open me-1"></i> Materi
                                    </a>
                                    <a class="btn btn-info btn-sm text-white" href="{{ route('courses.edit', $course->id) }}">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    {{-- Tombol Hapus sesuai method destroy --}}
                                    <form action="{{ route('courses.destroy', $course->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus kursus ini beserta seluruh modulnya?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-5 text-muted">
                                <i class="fas fa-book-open fa-3x mb-3 d-block"></i>
                                Belum ada kursus yang dibuat.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection