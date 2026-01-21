@extends('layouts.user')

@section('content')

{{-- 1. CUSTOM STYLE (Revisi Lebih Rapi & Responsive) --}}
<style>
    /* Font Import */
    @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');
    
    body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #f8fafc; }

    /* Hero Section */
    .hero-section {
        background: radial-gradient(circle at 10% 20%, #1e293b 0%, #0f172a 90%);
        color: white;
        padding: 60px 0 80px 0;
        position: relative;
        overflow: hidden;
    }

    /* Pattern Halus di Background */
    .hero-bg-pattern {
        position: absolute;
        top: 0; left: 0; width: 100%; height: 100%;
        background-image: linear-gradient(#334155 1px, transparent 1px), linear-gradient(90deg, #334155 1px, transparent 1px);
        background-size: 40px 40px;
        opacity: 0.05;
    }

    /* Card Floating Effect - Responsive Logic */
    .overlap-card {
        border: none;
        border-radius: 16px;
        box-shadow: 0 20px 40px -15px rgba(0,0,0,0.1); /* Shadow lebih halus */
        background: white;
        transition: transform 0.3s ease;
        position: relative;
        z-index: 10;
    }

    /* Logic Overlap: Hanya di Layar Besar (Desktop) */
    @media (min-width: 992px) {
        .hero-section { padding-bottom: 120px; } /* Ruang lebih bawah untuk ditimpa */
        .overlap-card { margin-top: -80px; } /* Menarik card ke atas */
    }
    
    @media (max-width: 991.98px) {
        .hero-section { padding-bottom: 40px; }
        .overlap-card { margin-top: 20px; } /* Di HP, margin normal */
    }

    /* Tombol Gradient */
    .btn-primary-custom {
        background: linear-gradient(135deg, #06b6d4 0%, #0ea5e9 100%);
        border: none;
        color: white;
        font-weight: 700;
        padding: 14px 20px;
        border-radius: 12px;
        transition: all 0.3s;
        box-shadow: 0 4px 6px -1px rgba(6, 182, 212, 0.3);
    }
    .btn-primary-custom:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 15px -3px rgba(6, 182, 212, 0.4);
        color: white;
    }

    /* Accordion Custom */
    .accordion-item {
        border: 1px solid #f1f5f9;
        margin-bottom: 10px;
        border-radius: 12px !important;
        overflow: hidden;
        box-shadow: 0 2px 4px rgba(0,0,0,0.02);
    }
    .accordion-button {
        background-color: white;
        font-weight: 600;
        color: #1e293b;
        padding: 16px 20px;
        box-shadow: none !important;
    }
    .accordion-button:not(.collapsed) {
        background-color: #f0f9ff; /* Biru sangat muda */
        color: #0284c7;
    }
    
    /* Placeholder Thumbnail jika gambar kosong */
    .thumbnail-placeholder {
        height: 200px;
        width: 100%;
        background: linear-gradient(45deg, #1e293b, #334155);
        display: flex;
        align-items: center;
        justify-content: center;
        color: rgba(255,255,255,0.2);
        font-size: 3rem;
    }
    
    .sticky-sidebar {
        position: sticky;
        top: 100px; /* Jarak dari atas saat discroll */
    }
</style>

{{-- DATA DUMMY (Untuk Tampilan) --}}
@php
    $dummyModules = [
        ['title' => 'Pengenalan & Persiapan', 'duration' => '45 Menit'],
        ['title' => 'Konsep Dasar Pemrograman', 'duration' => '1 Jam 20 Menit'],
        ['title' => 'Studi Kasus Project Nyata', 'duration' => '2 Jam'],
        ['title' => 'Deployment & Hosting', 'duration' => '50 Menit'],
    ];
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
                    {{ Str::limit($course->description ?? 'Pelajari materi ini dari dasar hingga mahir dengan kurikulum standar industri.', 150) }}
                </p>
                
                <div class="d-flex flex-wrap gap-4 text-sm text-white text-opacity-90 fw-medium">
                    <span class="d-flex align-items-center"><i class="fas fa-user-graduate me-2 text-info"></i> 1,240 Siswa</span>
                    <span class="d-flex align-items-center"><i class="fas fa-star me-2 text-warning"></i> 4.8/5.0</span>
                    <span class="d-flex align-items-center"><i class="fas fa-film me-2 text-info"></i> 12 Video</span>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="container pb-5">
    <div class="row">
        
        <div class="col-lg-8">
            
            {{-- ALERT TRANSAKSI (Hanya muncul jika ada riwayat) --}}
            @if(count($orders) > 0)
                <div class="overlap-card mb-4 p-4 border-start border-4 border-warning">
                    <h5 class="fw-bold mb-3 d-flex align-items-center">
                        <i class="fas fa-receipt me-2 text-warning"></i> Status Transaksi
                    </h5>
                    
                    @foreach($orders as $order)
                        <div class="d-flex flex-wrap justify-content-between align-items-center p-3 rounded bg-light mb-2 border border-light">
                            <div class="mb-2 mb-md-0">
                                <span class="fw-bold text-dark d-block text-uppercase small">#{{ $order->reference_id ?? $order->id }}</span>
                                <small class="text-muted">{{ $order->created_at->format('d M Y, H:i') }}</small>
                            </div>
                            
                            @if($order->payment_status == 'pending')
                                <button class="btn btn-warning btn-sm fw-bold px-3 text-white" onclick="startPayment('{{ $order->snap_token }}', '{{ $order->reference_id }}')">
                                    Bayar Sekarang
                                </button>
                            @elseif($order->payment_status == 'paid')
                                <span class="badge bg-success bg-opacity-10 text-success px-3 py-2 rounded-pill fw-bold">LUNAS</span>
                            @else
                                <span class="badge bg-danger bg-opacity-10 text-danger px-3 py-2 rounded-pill fw-bold">GAGAL</span>
                            @endif
                        </div>
                    @endforeach
                </div>
            @endif

            {{-- MENU TAB --}}
            <div class="d-flex gap-2 my-4 overflow-auto pb-2">
                <button class="btn btn-dark rounded-pill px-4 fw-bold shadow-sm">Tentang Kursus</button>
                <button class="btn btn-white border rounded-pill px-4 fw-bold text-muted hover-bg-light">Ulasan</button>
                <button class="btn btn-white border rounded-pill px-4 fw-bold text-muted hover-bg-light">Diskusi</button>
            </div>

            {{-- DESKRIPSI & MANFAAT --}}
            <div class="mb-5">
                <h4 class="fw-bold text-dark mb-3">Apa yang akan dipelajari?</h4>
                <div class="card border-0 shadow-sm bg-white p-4 mb-3" style="border-radius: 16px;">
                    <div class="row g-3">
                        <div class="col-md-6 d-flex align-items-start">
                            <i class="fas fa-check-circle text-success mt-1 me-2"></i>
                            <span class="text-secondary">Memahami konsep dasar {{ $course->title }}</span>
                        </div>
                        <div class="col-md-6 d-flex align-items-start">
                            <i class="fas fa-check-circle text-success mt-1 me-2"></i>
                            <span class="text-secondary">Best practice & clean code</span>
                        </div>
                        <div class="col-md-6 d-flex align-items-start">
                            <i class="fas fa-check-circle text-success mt-1 me-2"></i>
                            <span class="text-secondary">Studi kasus implementasi nyata</span>
                        </div>
                        <div class="col-md-6 d-flex align-items-start">
                            <i class="fas fa-check-circle text-success mt-1 me-2"></i>
                            <span class="text-secondary">Persiapan karir profesional</span>
                        </div>
                    </div>
                </div>
                <p class="text-muted" style="line-height: 1.8; text-align: justify;">
                    {{ $course->description ?? 'Kursus ini dirancang khusus untuk membantu Anda menguasai skill baru dengan metode pembelajaran yang terstruktur, praktis, dan mudah dipahami.' }}
                </p>
            </div>

            {{-- KURIKULUM --}}
            <div class="mb-5">
                <div class="d-flex justify-content-between align-items-end mb-3">
                    <h4 class="fw-bold text-dark mb-0">Kurikulum</h4>
                    <span class="text-muted small fw-bold">4 Modul • 12 Materi</span>
                </div>

                <div class="accordion" id="accordionSyllabus">
                    @foreach($dummyModules as $index => $module)
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="heading{{ $index }}">
                            <button class="accordion-button {{ $index == 0 ? '' : 'collapsed' }}" type="button" data-bs-toggle="collapse" data-bs-target="#collapse{{ $index }}">
                                <div class="d-flex justify-content-between w-100 me-3 align-items-center">
                                    <span class="fw-bold">Modul {{ $index + 1 }}: {{ $module['title'] }}</span>
                                    <small class="text-muted fw-normal bg-light px-2 py-1 rounded border">{{ $module['duration'] }}</small>
                                </div>
                            </button>
                        </h2>
                        <div id="collapse{{ $index }}" class="accordion-collapse collapse {{ $index == 0 ? 'show' : '' }}" data-bs-parent="#accordionSyllabus">
                            <div class="accordion-body bg-white pt-2">
                                <ul class="list-unstyled mb-0">
                                    <li class="d-flex align-items-center py-2 border-bottom border-light">
                                        <div class="bg-light rounded-circle p-2 me-3 text-primary"><i class="fas fa-play fa-sm"></i></div>
                                        <div>
                                            <span class="d-block text-dark fw-medium small">Video Pengantar</span>
                                        </div>
                                    </li>
                                    <li class="d-flex align-items-center py-2">
                                        <div class="bg-light rounded-circle p-2 me-3 text-warning"><i class="fas fa-file-alt fa-sm"></i></div>
                                        <div>
                                            <span class="d-block text-dark fw-medium small">Materi Bacaan & Quiz</span>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="overlap-card sticky-sidebar p-1">
                <div class="p-3">
                    {{-- THUMBNAIL AREA --}}
                    <div class="rounded-3 overflow-hidden position-relative mb-3 shadow-sm border">
                        @if($course->thumbnail_url)
                            <img src="{{ $course->thumbnail_url }}" class="img-fluid w-100" style="object-fit: cover; height: 200px;" alt="Course">
                        @else
                            {{-- Placeholder Elegan jika tidak ada gambar --}}
                            <div class="thumbnail-placeholder">
                                <i class="fas fa-code"></i>
                            </div>
                        @endif
                        
                        {{-- Overlay Play Icon --}}
                        <div class="position-absolute top-0 start-0 w-100 h-100 d-flex align-items-center justify-content-center bg-dark bg-opacity-10 hover-overlay">
                            <div class="bg-white rounded-circle p-3 shadow-lg text-primary">
                                <i class="fas fa-play"></i>
                            </div>
                        </div>
                    </div>

                    {{-- HARGA --}}
                    <div class="text-center mb-4">
                        <span class="text-muted text-decoration-line-through small">Rp 499.000</span>
                        <h2 class="fw-bold text-dark mb-0">Gratis <span class="badge bg-success bg-opacity-10 text-success fs-6 align-middle">PROMO</span></h2>
                    </div>
                    
                    {{-- BUTTON ACTION --}}
                    <div class="d-grid gap-2 mb-4">
                        <a href="{{ route('user.courses.learning', $course->slug) }}" class="btn-primary-custom text-center text-decoration-none">
                            Mulai Belajar Sekarang <i class="fas fa-arrow-right ms-2"></i>
                        </a>
                    </div>

                    {{-- FEATURES LIST --}}
                    <div class="bg-light p-3 rounded-3">
                        <h6 class="fw-bold mb-3 small text-uppercase text-muted">Termasuk dalam kursus:</h6>
                        <ul class="list-unstyled d-flex flex-column gap-2 text-sm text-dark mb-0">
                            <li class="d-flex align-items-center text-muted small"><i class="fas fa-check text-success me-2"></i> Akses Selamanya</li>
                            <li class="d-flex align-items-center text-muted small"><i class="fas fa-check text-success me-2"></i> 15 Sumber Daya Download</li>
                            <li class="d-flex align-items-center text-muted small"><i class="fas fa-check text-success me-2"></i> Sertifikat Penyelesaian</li>
                            <li class="d-flex align-items-center text-muted small"><i class="fas fa-check text-success me-2"></i> Akses di HP & TV</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

{{-- SCRIPT MIDTRANS --}}
<script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('services.midtrans.clientKey') }}"></script>
<script type="text/javascript">
    function startPayment(token, currentOrderId) {
        if(!token) { alert("Snap Token tidak ditemukan!"); return; }
        snap.pay(token, {
            onSuccess: function(result){ window.location.href = "{{ route('user.payment.success') }}?order_id=" + currentOrderId; },
            onPending: function(result){ alert("Menunggu pembayaran!"); location.reload(); },
            onError: function(result){ window.location.href = "{{ route('user.payment.failed') }}?order_id=" + currentOrderId; },
            onClose: function(){ alert('Anda menutup popup. Status akan diupdate.'); window.location.href = "{{ route('user.payment.failed') }}?order_id=" + currentOrderId; }
        });
    }
</script>

@endsection