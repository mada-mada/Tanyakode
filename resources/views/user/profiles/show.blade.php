@extends('layouts.user')

@section('title', 'Profil Saya')

@section('content')
<div class="container-fluid">
    <div class="d-flex align-items-center mb-5">
        <div class="position-relative">
            <img src="{{ $user->avatar_url ? asset('storage/' . $user->avatar_url) : 'https://ui-avatars.com/api/?name=' . urlencode($user->full_name ?? $user->username) . '&background=0a192f&color=64ffda' }}" 
                 class="rounded-circle border border-4 border-white shadow" 
                 style="width: 120px; height: 120px; object-fit: cover;" alt="Avatar">
            <span class="position-absolute bottom-0 end-0 badge rounded-pill bg-navy border border-light p-2">
                <i class="fa-solid fa-camera text-accent"></i>
            </span>
        </div>
        <div class="ms-4">
            <h2 class="fw-bold text-navy mb-1">{{ $user->full_name ?? $user->username }}</h2>
            <p class="text-muted mb-0">
                <i class="fa-solid fa-envelope me-1"></i> {{ $user->email }} 
                <span class="mx-2">|</span>
                <i class="fa-solid fa-user-tag me-1"></i> {{ ucfirst($user->role) }}
            </p>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-lg-8">
            <div class="navy-card shadow-sm h-100">
                <div class="navy-card-header bg-navy">
                    <h5 class="mb-0 fw-bold text-accent"><i class="fa-solid fa-id-card me-2"></i> Detail Informasi</h5>
                </div>
                <div class="navy-card-body">
                    <div class="row">
                        <div class="col-md-6 mb-4">
                            <label class="text-muted small fw-bold text-uppercase">Username</label>
                            <p class="fw-bold text-navy">{{ $user->username }}</p>
                        </div>
                        <div class="col-md-6 mb-4">
                            <label class="text-muted small fw-bold text-uppercase">Domisili</label>
                            <p class="fw-bold text-navy">{{ $user->domisili ?? '-' }}</p>
                        </div>
                        <hr>
                        <div class="col-md-6 mb-4 mt-2">
                            <label class="text-muted small fw-bold text-uppercase">NIS</label>
                            <p class="fw-bold text-navy">{{ $user->nis ?? '-' }}</p>
                        </div>
                        <div class="col-md-6 mb-4 mt-2">
                            <label class="text-muted small fw-bold text-uppercase">NISN</label>
                            <p class="fw-bold text-navy">{{ $user->nisn ?? '-' }}</p>
                        </div>
                        <div class="col-md-6 mb-4">
                            <label class="text-muted small fw-bold text-uppercase">Kelas / Grade</label>
                            <p class="fw-bold text-navy">Kelas {{ $user->grade ?? '-' }} ({{ $user->school_category ?? '-' }})</p>
                        </div>
                    </div>
                    
                    <div class="mt-4">
                        <a href="{{ route('user.profiles.edit', $user->id) }}" class="btn btn-navy px-4">
                            <i class="fa-solid fa-user-pen me-2"></i> Edit Profil
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="navy-card shadow-sm mb-4">
                <div class="navy-card-header bg-navy">
                    <h5 class="mb-0 fw-bold text-accent"><i class="fa-solid fa-school me-2"></i> Sekolah</h5>
                </div>
                <div class="navy-card-body text-center">
                    @if($user->school_id)
                        <div class="rounded-circle bg-navy p-4 d-inline-block mb-3">
                            <i class="fa-solid fa-building-columns fa-2x text-accent"></i>
                        </div>
                        <h6 class="fw-bold text-navy">{{ $user->school->name }}</h6>
                        <p class="text-muted small">{{ $user->school->address ?? 'Alamat belum diatur' }}</p>
                        <button class="btn btn-sm btn-outline-navy mt-2 w-100" onclick="toggleJoinForm()">
                            Ganti Instansi
                        </button>
                    @else
                        <div class="alert alert-warning py-4">
                            <i class="fa-solid fa-triangle-exclamation fa-2x mb-3"></i>
                            <p class="mb-0 small">Anda belum terdaftar di instansi sekolah manapun.</p>
                        </div>
                        <button class="btn btn-navy w-100" onclick="toggleJoinForm()">
                            Gabung Sekolah Sekarang
                        </button>
                    @endif

                    <div id="profileJoinForm" class="d-none mt-4 text-start pt-3 border-top">
                        <form action="{{ route('user.join_school') }}" method="POST">
                            @csrf
                            <label class="small fw-bold text-muted">TOKEN SEKOLAH</label>
                            <div class="input-group">
                                <input type="text" name="token_code" class="form-control" placeholder="Contoh: SMAN1-ABC" required>
                                <button type="submit" class="btn btn-navy"><i class="fa-solid fa-paper-plane"></i></button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    :root {
        --navy-dark: #0a192f;
        --accent-blue: #64ffda;
    }
    .text-navy { color: var(--navy-dark) !important; }
    .text-accent { color: var(--accent-blue) !important; }
    .bg-navy { background-color: var(--navy-dark) !important; }
    .btn-navy { background-color: var(--navy-dark); color: var(--accent-blue); border: none; transition: 0.3s; }
    .btn-navy:hover { background-color: #172a45; color: var(--accent-blue); transform: translateY(-2px); }
    .btn-outline-navy { border: 1px solid var(--navy-dark); color: var(--navy-dark); transition: 0.3s; }
    .btn-outline-navy:hover { background: var(--navy-dark); color: var(--accent-blue); }
    .navy-card { background: white; border-radius: 15px; overflow: hidden; }
    .navy-card-header { padding: 1.2rem 1.5rem; }
    .navy-card-body { padding: 1.5rem; }
</style>

<script>
    function toggleJoinForm() {
        document.getElementById('profileJoinForm').classList.toggle('d-none');
    }
</script>
@endsection