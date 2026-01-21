@extends('layouts.user')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <img src="{{ $course->thumbnail_url ?? 'https://via.placeholder.com/800x400' }}" class="card-img-top" alt="{{ $course->title }}">
                <div class="card-body">
                    <h2 class="card-title">{{ $course->title }}</h2>
                    <p class="card-text">{{ $course->description }}</p>
                    
                    <div class="mt-4 mb-4">
                        {{-- Jika user belum punya transaksi PENDING untuk course ini, tampilkan tombol beli --}}
                        @if(!$pendingTransaction)
                            <form action="{{ route('user.payment.process') }}" method="POST">
                                @csrf
                                <input type="hidden" name="course_id" value="{{ $course->id }}">
                                
                                {{-- Area Voucher (Opsional) --}}
                                <div class="input-group mb-3">
                                    <input type="text" id="voucher-code" class="form-control" placeholder="Punya kode voucher?">
                                    <button class="btn btn-outline-secondary" type="button" onclick="checkVoucher()">Cek</button>
                                </div>
                                <input type="hidden" name="voucher_id" id="hidden-voucher-id">
                                <div id="voucher-message" class="mb-2"></div>

                                <button type="submit" class="btn btn-lg btn-success w-100">
                                    Beli Course Ini (Rp {{ number_format($course->price, 0, ',', '.') }})
                                </button>
                            </form>
                        @else
                            <div class="alert alert-warning">
                                Anda memiliki transaksi yang belum diselesaikan. Silakan cek di bawah.
                            </div>
                        @endif
                    </div>
                    <hr>
                    <h3>Riwayat Pesanan</h3>
                    
                    @if($orders->isEmpty())
                        <p class="text-muted">Belum ada riwayat pesanan.</p>
                    @else
                        @foreach($orders as $order)
                        <div class="card mb-3 p-3 border-{{ $order->payment_status == 'paid' ? 'success' : ($order->payment_status == 'pending' ? 'warning' : 'danger') }}">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <strong>Order ID:</strong> {{ $order->reference_id ?? $order->id }} <br>
                                    <strong>Status:</strong> {{ $order->payment_status }}
                                </div>

                                <div>
                                    @if($order->payment_status == 'pending')
                                        {{-- Tombol Bayar untuk Transaksi Pending --}}
                                        <button class="btn btn-primary" onclick="startPayment('{{ $order->snap_token }}', '{{ $order->reference_id }}')">
                                            Bayar Sekarang
                                        </button>
                                    
                                    @elseif($order->payment_status == 'cancelled' || $order->payment_status == 'failed' || $order->payment_status == 'expired')
                                        {{-- Tombol Beli Lagi jika Gagal --}}
                                        <form action="{{ route('user.payment.retry', $order->id) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="btn btn-warning">
                                                Beli Lagi
                                            </button>
                                        </form>
                                        
                                    @elseif($order->payment_status == 'paid')
                                        <span class="badge bg-success">Lunas</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        @endforeach
                    @endif

                </div>
            </div>
        </div>
    </div>
</div>

{{-- ... Bagian atas HTML tetap sama ... --}}

{{-- LOGIKA SCRIPT MIDTRANS --}}
@php
    $isProduction = config('services.midtrans.isProduction');
    $snapUrl = $isProduction 
        ? 'https://app.midtrans.com/snap/snap.js' 
        : 'https://app.sandbox.midtrans.com/snap/snap.js';
@endphp

{{-- 1. Load Script Midtrans --}}
<script src="{{ $snapUrl }}" data-client-key="{{ config('services.midtrans.clientKey') }}"></script>

<script type="text/javascript">
    // Debugging: Cek apakah Client Key terbaca
    console.log("Midtrans Client Key:", "{{ config('services.midtrans.clientKey') }}");
    
    // Fungsi Utama Pembayaran
    function startPayment(token, orderId) {
        // Cek apakah token ada
        if(!token) {
            alert("Maaf, Token Pembayaran belum digenerate. Silakan refresh halaman.");
            return;
        }

        // Cek apakah library snap sudah dimuat
        if(typeof window.snap === "undefined") {
            alert("Gagal memuat Midtrans Snap. Cek Client Key di config/services.php");
            return;
        }

        snap.pay(token, {
            onSuccess: function(result){
                window.location.href = "{{ route('user.payment.success') }}?order_id=" + orderId + "&transaction_status=" + result.transaction_status;
            },
            onPending: function(result){
                alert("Menunggu pembayaran!");
                location.reload();
            },
            onError: function(result){
                window.location.href = "{{ route('user.payment.failed') }}?order_id=" + orderId;
            },
            onClose: function(){
                alert('Anda menutup popup pembayaran.');
            }
        });
    }

    // AUTO-TRIGGER: Jika ada pending transaction, otomatis buka popup saat halaman dimuat
    // (Opsional: Aktifkan jika ingin user langsung melihat popup setelah klik Beli)
    document.addEventListener("DOMContentLoaded", function(event) { 
        var pendingToken = "{{ $snapToken }}";
        var pendingOrderId = "{{ $pendingTransaction ? $pendingTransaction->reference_id : '' }}";
        
        // Hanya auto-open jika user baru saja klik beli (bisa dicek lewat flash message session, tapi ini cara simpel)
        // Uncomment baris di bawah jika ingin popup langsung muncul:
        // if(pendingToken) { startPayment(pendingToken, pendingOrderId); }
    });
</script>
@endsection