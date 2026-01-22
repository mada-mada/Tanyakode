@extends('layouts.user')

@section('content')

{{-- STYLE KHUSUS --}}
<style>
    @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');
    body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #f8fafc; }
    .hero-section { background: radial-gradient(circle at 10% 20%, #1e293b 0%, #0f172a 90%); color: white; padding: 60px 0 80px; position: relative; overflow: hidden; }
    .hero-bg-pattern { position: absolute; top: 0; left: 0; width: 100%; height: 100%; background-image: linear-gradient(#334155 1px, transparent 1px), linear-gradient(90deg, #334155 1px, transparent 1px); background-size: 40px 40px; opacity: 0.05; }
    .main-content { margin-top: -40px; position: relative; z-index: 10; }
    .card-clean { background: white; border: 1px solid #e2e8f0; border-radius: 16px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05); overflow: hidden; }
    .btn-primary-custom { background: linear-gradient(135deg, #06b6d4 0%, #0ea5e9 100%); border: none; color: white; font-weight: 700; padding: 14px 20px; border-radius: 12px; transition: all 0.3s; display: block; width: 100%; cursor: pointer; }
    .thumbnail-box { height: 200px; width: 100%; position: relative; background: linear-gradient(45deg, #1e293b, #334155); display: flex; align-items: center; justify-content: center; border-radius: 12px; overflow: hidden; margin-bottom: 20px; }
    .thumbnail-box img { width: 100%; height: 100%; object-fit: cover; }
</style>

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
                <span class="badge bg-white bg-opacity-10 border border-white border-opacity-20 text-white px-3 py-2 rounded-pill fw-bold mb-3">
                    {{ strtoupper($course->level ?? 'GENERAL') }}
                </span>
                <h1 class="display-5 fw-bold mb-3">{{ $course->title }}</h1>
                <p class="text-white text-opacity-75 mb-4 lead fs-6">{{ Str::limit($course->description, 150) }}</p>
            </div>
        </div>
    </div>
</div>

{{-- B. MAIN CONTENT --}}
<div class="container main-content pb-5 mt-2">
    <div class="row align-items-start">
        <div class="col-lg-8 mb-5">
            <div class="card-clean p-4 p-md-5 mb-4">
                <h4 class="fw-bold">Tentang Kursus</h4>
                <p class="text-muted">{{ $course->description }}</p>
            </div>
        </div>

        {{-- SIDEBAR PEMBAYARAN --}}
        <div class="col-lg-4 mt-5">
            <div class="card-clean p-4">
                <div class="thumbnail-box shadow-sm">
                    @if(!empty($course->cover))
                        <img src="{{ asset('storage/' . $course->cover) }}" alt="Cover">
                    @else
                        <div class="text-white fs-1"><i class="fas fa-laptop-code"></i></div>
                    @endif
                </div>

                <div class="mb-4">
                    <div class="d-flex justify-content-between">
                        <span class="text-muted">Harga</span>
                        <span class="fw-bold">Rp {{ number_format($course->price, 0, ',', '.') }}</span>
                    </div>
                    <div id="discount-row" style="display: none;" class="d-flex justify-content-between text-success">
                        <span>Voucher</span>
                        <span id="display-discount">- Rp 0</span>
                    </div>
                    <hr>
                    <div class="d-flex justify-content-between">
                        <span class="fw-bold">Total</span>
                        <span class="h4 fw-bold text-primary" id="display-final-price">Rp {{ number_format($course->price, 0, ',', '.') }}</span>
                    </div>
                </div>

                <div class="d-grid gap-2">
                    @auth
                        @if(auth()->user()->hasPurchased($course->id))
                            <a href="{{ route('user.courses.learning', ['slug' => $course->slug]) }}" class="btn btn-success btn-lg w-100 fw-bold">Lanjut Belajar</a>
                        @else
                            <button id="pay-button" class="btn-primary-custom">Beli Sekarang <i class="fas fa-shopping-cart ms-2"></i></button>
                            <div id="loading-payment" style="display: none;" class="text-center mt-2">
                                <div class="spinner-border spinner-border-sm text-primary"></div> memproses...
                            </div>
                        @endif
                    @else
                        <a href="{{ route('login') }}" class="btn btn-secondary">Login untuk Membeli</a>
                    @endauth
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
    {{-- Midtrans Snap JS --}}
    <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('services.midtrans.clientKey') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script type="text/javascript">
        document.addEventListener('DOMContentLoaded', function() {
            console.log("DEBUG: Script dimuat sempurna.");

            const payButton = document.getElementById('pay-button');
            const loadingIndicator = document.getElementById('loading-payment');
            let currentVoucherId = null;

            if (payButton) {
                payButton.addEventListener('click', async function(e) {
                    e.preventDefault();
                    
                    // 1. Cek Klik
                    console.log("DEBUG: Tombol Beli diklik.");
                    alert("Proses Dimulai: Menghubungi Server...");

                    payButton.disabled = true;
                    payButton.innerHTML = 'Memuat...';
                    loadingIndicator.style.display = 'block';

                    try {
                        // 2. Cek Request ke Controller
                        console.log("DEBUG: Mengirim request ke {{ route('user.payment.process') }}");
                        
                        const response = await fetch("{{ route('user.payment.process') }}", {
                            method: "POST",
                            headers: {
                                "Content-Type": "application/json",
                                "X-CSRF-TOKEN": "{{ csrf_token() }}",
                                "Accept": "application/json"
                            },
                            body: JSON.stringify({
                                course_id: "{{ $course->id }}",
                                voucher_id: currentVoucherId
                            })
                        });

                        // 3. Cek Response Mentah (Catch Error 500/HTML)
                        const rawText = await response.text();
                        console.log("DEBUG: Raw Response Server:", rawText);

                        let data;
                        try {
                            data = JSON.parse(rawText);
                        } catch (e) {
                            console.error("DEBUG: Gagal parse JSON. Response bukan JSON.");
                            alert("EROR KRITIS: Server tidak mengembalikan JSON. Cek Console (F12)!");
                            return;
                        }

                        console.log("DEBUG: Data JSON parsed:", data);

                        if (data.status === 'success' || data.status === 'pending') {
                            console.log("DEBUG: Token ditemukan, memanggil Snap Midtrans...");
                            
                            if (typeof window.snap === 'undefined') {
                                alert("EROR: Library Snap.js Midtrans tidak terdeteksi! Cek koneksi internet.");
                                return;
                            }

                            window.snap.pay(data.snap_token, {
                                onSuccess: function(result) { window.location.href = "{{ route('user.payment.success') }}?order_id=" + result.order_id; },
                                onPending: function(result) { alert("Pembayaran Pending"); },
                                onError: function(result) { alert("Pembayaran Gagal"); },
                                onClose: function() { alert("Anda menutup popup sebelum selesai."); }
                            });
                        } else {
                            alert("Gagal: " + (data.message || "Terjadi kesalahan di server."));
                        }

                    } catch (error) {
                        console.error("DEBUG: Error Catch JS:", error);
                        alert("EROR JS: " + error.message);
                    } finally {
                        payButton.disabled = false;
                        payButton.innerHTML = 'Beli Sekarang <i class="fas fa-shopping-cart ms-2"></i>';
                        loadingIndicator.style.display = 'none';
                    }
                });
            } else {
                console.warn("DEBUG: Tombol pay-button tidak ditemukan di DOM. Mungkin user sudah beli atau belum login.");
            }
        });
    </script>
@endpush