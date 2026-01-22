@extends('layouts.user')

@section('content')

{{-- STYLE KHUSUS: Layout Stabil & Bersih --}}
<style>
    @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');
    
    body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #f8fafc; }

    /* HERO SECTION */
    .hero-section {
        background: radial-gradient(circle at 10% 20%, #1e293b 0%, #0f172a 90%);
        color: white;
        padding-top: 60px;
        padding-bottom: 80px;
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

    .main-content {
        margin-top: -40px;
        position: relative;
        z-index: 10;
    }

    @media (max-width: 991.98px) {
        .hero-section { padding-bottom: 40px; }
        .main-content { margin-top: 0; }
    }

    .card-clean {
        background: white;
        border: 1px solid #e2e8f0;
        border-radius: 16px;
        box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05);
        overflow: hidden;
    }

    .sidebar-sticky {
        position: -webkit-sticky;
        position: sticky;
        top: 100px;
        z-index: 99;
    }

    .btn-primary-custom {
        background: linear-gradient(135deg, #06b6d4 0%, #0ea5e9 100%);
        border: none; color: white; font-weight: 700; padding: 14px 20px; border-radius: 12px;
        transition: all 0.3s; display: block; width: 100%;
        box-shadow: 0 4px 6px -1px rgba(6, 182, 212, 0.3);
        cursor: pointer;
    }
    .btn-primary-custom:hover { 
        transform: translateY(-2px); 
        box-shadow: 0 10px 15px -3px rgba(6, 182, 212, 0.4); color: white; 
        background: linear-gradient(135deg, #0ea5e9 0%, #06b6d4 100%);
    }
    .btn-primary-custom:disabled {
        background: #cbd5e1;
        transform: none;
        box-shadow: none;
        cursor: not-allowed;
    }

    .accordion-item { border: 1px solid #f1f5f9; margin-bottom: 10px; border-radius: 12px !important; overflow: hidden; }
    .accordion-button { background-color: white; font-weight: 600; color: #1e293b; padding: 16px 20px; box-shadow: none !important; }
    .accordion-button:not(.collapsed) { background-color: #f0f9ff; color: #0284c7; }

    .thumbnail-box {
        height: 200px; width: 100%; position: relative;
        background: linear-gradient(45deg, #1e293b, #334155);
        display: flex; align-items: center; justify-content: center; 
        border-radius: 12px; overflow: hidden; margin-bottom: 20px;
    }
    .thumbnail-box img { width: 100%; height: 100%; object-fit: cover; }
    .thumbnail-placeholder { color: rgba(255,255,255,0.2); font-size: 3rem; }
    
    .tab-btn { border-radius: 50px; padding: 8px 24px; font-weight: 600; }
</style>

{{-- LOGIKA HITUNG DATA --}}
@php
    $totalContent = 0;
    if($course->modules) {
        foreach($course->modules as $mod) {
            $totalContent += $mod->contents ? $mod->contents->count() : 0;
        }
    }
@endphp

{{-- A. HERO SECTION --}}
<div class="hero-section">
    <div class="hero-bg-pattern"></div>
    <div class="container position-relative">
        <div class="row">
            <div class="col-lg-8">
                <div class="mb-3">
                    <span class="badge bg-white bg-opacity-10 border border-white border-opacity-20 text-white px-3 py-2 rounded-pill fw-bold">
                        {{ strtoupper($course->level ?? 'GENERAL') }}
                    </span>
                </div>
                <h1 class="display-5 fw-bold mb-3 lh-sm">{{ $course->title }}</h1>
                <p class="text-white text-opacity-75 mb-4 lead fs-6" style="max-width: 600px; line-height: 1.7;">
                    {{ Str::limit($course->description, 150) }}
                </p>
                <div class="d-flex flex-wrap gap-4 text-sm text-white text-opacity-90 fw-medium">
                    <span class="me-3"><i class="fas fa-book-open me-2"></i> {{ $course->modules ? $course->modules->count() : 0 }} Modul</span>
                    <span><i class="fas fa-film me-2"></i> {{ $totalContent }} Materi</span>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- B. MAIN CONTENT --}}
<div class="container main-content pb-5 mt-2">
    <div class="row align-items-start">
        
        {{-- 1. KOLOM KIRI (Konten Utama) --}}
        <div class="col-lg-8 mb-5">
            {{-- Tab Menu --}}
            <div class="d-flex gap-2 mb-4 mt-3">
                <button class="btn btn-dark shadow-sm tab-btn">Tentang Kursus</button>
                <button class="btn btn-white border text-muted bg-white hover-bg-light tab-btn">Ulasan</button>
            </div>

            {{-- Kartu Deskripsi --}}
            <div class="card-clean p-4 p-md-5 mb-4">
                <p class="text-muted mb-0" style="line-height: 1.8; text-align: justify;">
                    {{ $course->description }}
                </p>
            </div>

            {{-- Kurikulum --}}
            <div class="mb-4">
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
                                    <span class="fw-bold">{{ $module->title }}</span>
                                    <small class="text-muted bg-white px-2 py-1 rounded border">
                                        {{ $module->contents ? $module->contents->count() : 0 }} Materi
                                    </small>
                                </div>
                            </button>
                        </h2>
                        <div id="collapse{{ $module->id }}" class="accordion-collapse collapse {{ $index == 0 ? 'show' : '' }}" data-bs-parent="#accordionSyllabus">
                            <div class="accordion-body bg-white pt-2">
                                <ul class="list-unstyled mb-0">
                                    @forelse($module->contents as $content)
                                        <li class="d-flex align-items-center py-2 border-bottom last:border-0">
                                            @if(Str::contains(strtolower($content->content_type ?? 'video'), 'video'))
                                                <div class="bg-light rounded-circle p-2 me-3 text-primary"><i class="fas fa-play fa-sm"></i></div>
                                            @else
                                                <div class="bg-light rounded-circle p-2 me-3 text-warning"><i class="fas fa-file-alt fa-sm"></i></div>
                                            @endif
                                            <span class="text-dark">{{ $content->title }}</span>
                                        </li>
                                    @empty
                                        <li class="text-muted small">Belum ada materi.</li>
                                    @endforelse
                                </ul>
                            </div>
                        </div>
                    </div>
                    @empty
                        <div class="alert alert-light border text-center">Belum ada kurikulum.</div>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- 2. KOLOM KANAN (Sidebar Pembayaran) --}}
        <div class="col-lg-4 mt-5">
            <div class="sidebar-sticky"> 
                <div class="card-clean p-4">
                    
                    {{-- Thumbnail --}}
                    <div class="thumbnail-box shadow-sm">
                        @if(!empty($course->cover) && file_exists(storage_path('app/public/' . $course->cover)))
                            <img src="{{ asset('storage/' . $course->cover) }}" alt="{{ $course->title }}">
                        @else
                            <div class="thumbnail-placeholder"><i class="fas fa-laptop-code"></i></div>
                        @endif
                        <div class="position-absolute bg-white rounded-circle d-flex align-items-center justify-content-center shadow" style="width: 50px; height: 50px;">
                            <i class="fas fa-play text-primary ml-1"></i>
                        </div>
                    </div>

                    {{-- === FITUR VOUCHER & HARGA DINAMIS === --}}
                    
                    {{-- Form Voucher --}}
                    @auth
                        @if($course->price > 0 && !auth()->user()->hasPurchased($course->id))
                            <div class="mb-3">
                                <label class="form-label small font-weight-bold text-muted text-uppercase">Punya Voucher?</label>
                                <div class="input-group">
                                    <input type="text" class="form-control form-control-sm" id="voucher-code" placeholder="Kode Voucher">
                                    <button class="btn btn-dark btn-sm" type="button" id="apply-voucher-btn">Gunakan</button>
                                </div>
                                <small id="voucher-feedback" class="d-block mt-1"></small>
                            </div>
                        @endif
                    @endauth

                    {{-- Rincian Harga --}}
                    <div class="mb-4">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <span class="text-muted small">Harga Kursus</span>
                            @if($course->price == 0)
                                <span class="fw-bold text-success">GRATIS</span>
                            @else
                                <span class="fw-bold text-dark" id="display-original-price">Rp {{ number_format($course->price, 0, ',', '.') }}</span>
                            @endif
                        </div>

                        {{-- Baris Diskon (Hidden Default) --}}
                        <div class="d-flex justify-content-between align-items-center mb-1 text-success" id="discount-row" style="display: none;">
                            <span class="small">Diskon Voucher</span>
                            <span class="small fw-bold" id="display-discount">- Rp 0</span>
                        </div>

                        <hr class="my-2 border-light">

                        <div class="d-flex justify-content-between align-items-center">
                            <span class="font-weight-bold text-dark">Total Bayar</span>
                            @if($course->price == 0)
                                <span class="h4 text-success font-weight-bold mb-0">GRATIS</span>
                            @else
                                <span class="h4 text-primary font-weight-bold mb-0" id="display-final-price">Rp {{ number_format($course->price, 0, ',', '.') }}</span>
                            @endif
                        </div>
                    </div>

                    {{-- TOMBOL ACTION & PAYMENT LOGIC --}}
                    <div class="d-grid gap-2 mb-4">
                        @auth
                            {{-- LOGIKA UTAMA: Cek Pembelian Menggunakan Model User --}}
                            @if(auth()->user()->hasPurchased($course->id))
                                
                                {{-- JIKA SUDAH BELI: Tampilkan Tombol Lanjut Belajar --}}
                                <a href="{{ route('user.courses.learning', ['slug' => $course->slug]) }}" class="btn btn-success btn-lg btn-block w-100 font-weight-bold" style="border-radius: 12px;">
                                    <i class="fas fa-play-circle mr-2"></i> Lanjut Belajar
                                </a>

                            @else
                                {{-- JIKA BELUM BELI --}}
                                @if($course->price > 0)
                                    {{-- Tombol Beli --}}
                                    <button id="pay-button" class="btn-primary-custom text-center text-decoration-none">
                                        Beli Sekarang <i class="fas fa-shopping-cart ms-2"></i>
                                    </button>
                                @else
                                    {{-- Tombol Gratis --}}
                                    <a href="{{ route('user.courses.learning', ['slug' => $course->slug]) }}" class="btn-primary-custom text-center text-decoration-none">
                                        Mulai Gratis <i class="fas fa-arrow-right ms-2"></i>
                                    </a>
                                @endif
                                
                                {{-- Indikator Loading --}}
                                <div id="loading-payment" class="text-center mt-2" style="display: none;">
                                    <div class="spinner-border text-primary spinner-border-sm" role="status">
                                        <span class="sr-only">Loading...</span>
                                    </div>
                                    <span class="text-muted small ml-2">Memproses pembayaran...</span>
                                </div>
                            @endif

                        @else
                            <a href="{{ route('login') }}" class="btn btn-secondary text-center text-decoration-none">
                                Login untuk Membeli <i class="fas fa-lock ms-2"></i>
                            </a>
                        @endauth
                    </div>

                    {{-- Fitur --}}
                    <div class="bg-light p-3 rounded-3">
                        <h6 class="fw-bold mb-3 small text-uppercase text-muted">Benefit Kursus:</h6>
                        <ul class="list-unstyled d-flex flex-column gap-2 text-sm text-dark mb-0">
                            <li class="d-flex align-items-center text-muted small"><i class="fas fa-clock text-success me-2"></i> Akses Selamanya</li>
                            <li class="d-flex align-items-center text-muted small"><i class="fas fa-mobile-alt text-success me-2"></i> Akses HP & PC</li>
                            <li class="d-flex align-items-center text-muted small"><i class="fas fa-certificate text-success me-2"></i> Sertifikat Penyelesaian</li>
                        </ul>
                    </div>

                </div>
            </div>
        </div>

    </div>
</div>
@endsection

@push('scripts')
    {{-- SweetAlert2 untuk Notifikasi --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    {{-- Midtrans Snap JS --}}
    <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('services.midtrans.clientKey') }}"></script>

    <script type="text/javascript">
        document.addEventListener('DOMContentLoaded', function() {
            // --- 1. SETUP VARIABLE ---
            const payButton = document.getElementById('pay-button');
            const voucherBtn = document.getElementById('apply-voucher-btn');
            const voucherInput = document.getElementById('voucher-code');
            const loadingIndicator = document.getElementById('loading-payment');
            
            // State Harga & Voucher
            let currentVoucherId = null; 
            let originalPrice = {{ $course->price }};
            
            // Helper Format Rupiah
            const formatRupiah = (number) => {
                return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(number);
            }

            // --- 2. LOGIKA CEK VOUCHER ---
            if (voucherBtn) {
                voucherBtn.addEventListener('click', async function() {
                    const code = voucherInput.value.trim();
                    const feedback = document.getElementById('voucher-feedback');
                    const discountRow = document.getElementById('discount-row');
                    const displayDiscount = document.getElementById('display-discount');
                    const displayFinal = document.getElementById('display-final-price');

                    if (!code) {
                        feedback.innerHTML = '<span class="text-danger">Masukkan kode voucher.</span>';
                        return;
                    }

                    // UI Loading
                    voucherBtn.disabled = true;
                    voucherBtn.innerText = '...';

                    try {
                        const response = await fetch("{{ route('user.payment.check_voucher') }}", {
                            method: "POST",
                            headers: {
                                "Content-Type": "application/json",
                                "X-CSRF-TOKEN": "{{ csrf_token() }}",
                                "Accept": "application/json"
                            },
                            body: JSON.stringify({
                                code: code,
                                course_id: "{{ $course->id }}"
                            })
                        });

                        const data = await response.json();

                        if (response.ok && data.status === 'success') {
                            // Sukses: Simpan ID Voucher
                            currentVoucherId = data.data.voucher_id;
                            
                            // Update UI
                            feedback.innerHTML = `<span class="text-success"><i class="fas fa-check-circle"></i> ${data.message}</span>`;
                            voucherInput.classList.add('is-valid');
                            voucherInput.classList.remove('is-invalid');
                            voucherInput.disabled = true; 
                            voucherBtn.innerText = 'Terpasang';

                            // Update Harga di Tampilan
                            discountRow.style.display = 'flex';
                            displayDiscount.innerText = '- ' + formatRupiah(data.data.discount);
                            displayFinal.innerText = formatRupiah(data.data.final_price);

                            Swal.fire({
                                icon: 'success',
                                title: 'Voucher Berhasil!',
                                text: `Anda hemat ${formatRupiah(data.data.discount)}`,
                                timer: 1500,
                                showConfirmButton: false
                            });

                        } else {
                            throw new Error(data.message || 'Voucher tidak valid.');
                        }

                    } catch (error) {
                        console.error(error);
                        currentVoucherId = null; // Reset voucher
                        
                        feedback.innerHTML = `<span class="text-danger"><i class="fas fa-times-circle"></i> ${error.message}</span>`;
                        voucherInput.classList.add('is-invalid');
                        voucherBtn.disabled = false;
                        voucherBtn.innerText = 'Gunakan';
                        
                        // Sembunyikan baris diskon
                        discountRow.style.display = 'none';
                        displayFinal.innerText = formatRupiah(originalPrice);
                    }
                });
            }

            // --- 3. LOGIKA PEMBAYARAN (PROCESS) ---
            if (payButton) {
                payButton.addEventListener('click', async function(e) {
                    e.preventDefault();

                    // UI Loading
                    payButton.disabled = true;
                    payButton.innerHTML = 'Memuat...';
                    loadingIndicator.style.display = 'block';

                    try {
                        const response = await fetch("{{ route('user.payment.process') }}", {
                            method: "POST",
                            headers: {
                                "Content-Type": "application/json",
                                "X-CSRF-TOKEN": "{{ csrf_token() }}",
                                "Accept": "application/json"
                            },
                            body: JSON.stringify({
                                course_id: "{{ $course->id }}",
                                price: "{{ $course->price }}",
                                voucher_id: currentVoucherId // Kirim ID Voucher jika ada
                            })
                        });

                        const data = await response.json();

                        // Reset UI
                        loadingIndicator.style.display = 'none';
                        payButton.disabled = false;
                        payButton.innerHTML = 'Beli Sekarang <i class="fas fa-shopping-cart ms-2"></i>';

                        // --- HANDLE RESPONSE ---
                        
                        // KASUS 1: Ternyata sudah lunas (User reload/tutup tab sebelumnya)
                        if (data.status === 'paid') {
                            Swal.fire({
                                icon: 'success',
                                title: 'Pembayaran Ditemukan!',
                                text: 'Sistem menemukan pembayaran Anda sebelumnya. Halaman akan dimuat ulang.',
                                timer: 2000,
                                showConfirmButton: false
                            }).then(() => {
                                window.location.reload(); 
                            });
                        } 
                        // KASUS 2: Transaksi Baru / Lanjut Bayar (Munculkan Snap)
                        else if (data.status === 'success' || data.status === 'pending') {
                            window.snap.pay(data.snap_token, {
                                onSuccess: function(result) {
                                    window.location.href = "{{ route('user.payment.success') }}?order_id=" + result.order_id + "&transaction_status=settlement";
                                },
                                onPending: function(result) {
                                    Swal.fire('Menunggu', 'Selesaikan pembayaran Anda.', 'info');
                                },
                                onError: function(result) {
                                    Swal.fire('Gagal', 'Pembayaran gagal.', 'error');
                                },
                                onClose: function() {
                                    Swal.fire('Dibatalkan', 'Anda menutup popup.', 'warning');
                                }
                            });
                        } 
                        // KASUS 3: Gratis (Diskon 100%)
                        else if (data.status === 'free') {
                            window.location.href = data.redirect_url;
                        } 
                        // KASUS 4: Error
                        else {
                            Swal.fire('Gagal', data.message || 'Gagal memproses.', 'error');
                        }

                    } catch (error) {
                        console.error('Error:', error);
                        loadingIndicator.style.display = 'none';
                        payButton.disabled = false;
                        payButton.innerHTML = 'Coba Lagi';
                        Swal.fire('Oops...', 'Terjadi kesalahan sistem.', 'error');
                    }
                });
            }
        });
    </script>
@endpush