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
                    
                    <hr>
                    <h3>Riwayat Pesanan</h3>
                    
                    @foreach($orders as $order)
                    <div class="card mb-3 p-3 border-{{ $order->payment_status == 'paid' ? 'success' : ($order->payment_status == 'pending' ? 'warning' : 'danger') }}">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <strong>Order ID:</strong> {{ $order->reference_id ?? $order->id }} <br>
                                <strong>Status:</strong> {{ $order->payment_status }}
                            </div>

                            <div>
                                @if($order->payment_status == 'pending')
                                    {{-- PERBAIKAN 1: Panggil fungsi JS langsung dengan onclick & kirim Token spesifik --}}
                                    <button class="btn btn-primary" onclick="startPayment('{{ $order->snap_token }}', '{{ $order->reference_id }}')">
                                        Bayar Sekarang
                                    </button>
                                
                                @elseif($order->payment_status == 'cancelled' || $order->payment_status == 'failed' || $order->payment_status == 'expired')
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

                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('services.midtrans.clientKey') }}"></script>

<script type="text/javascript">
    // Tangkap 2 parameter di sini
    function startPayment(token, currentOrderId) {
        
        if(!token) {
            alert("Snap Token tidak ditemukan!");
            return;
        }

        snap.pay(token, {
            // Jika SUKSES
            onSuccess: function(result){
                // Untuk sukses, result biasanya aman, tapi pakai currentOrderId juga boleh
                window.location.href = "{{ route('user.payment.success') }}?order_id=" + currentOrderId;
            },
            
            // Jika PENDING
            onPending: function(result){
                alert("Menunggu pembayaran!");
                location.reload();
            },
            
            // Jika ERROR
            onError: function(result){
                // PENTING: Gunakan currentOrderId, JANGAN result.order_id
                window.location.href = "{{ route('user.payment.failed') }}?order_id=" + currentOrderId;
            },
            
            // Jika DITUTUP (CLOSE)
            onClose: function(){
                alert('Anda menutup popup. Status akan diupdate.');
                // PENTING: Gunakan currentOrderId, JANGAN result.order_id
                window.location.href = "{{ route('user.payment.failed') }}?order_id=" + currentOrderId;
            }
        });
    }
</script>
@endsection