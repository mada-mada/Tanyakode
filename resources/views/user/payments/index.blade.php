@extends('layouts.user')

@section('title', 'Riwayat Pembayaran')

@section('content')
    <div class="container py-5">
        <div class="row">
            <div class="col-12">
                <h2 class="mb-4">Riwayat Pembayaran</h2>

                @if ($orders->isEmpty())
                    <div class="alert alert-info">
                        <i class="fa-solid fa-info-circle"></i> Anda belum memiliki riwayat pembayaran.
                    </div>
                @else
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>Tanggal</th>
                                    <th>Kursus</th>
                                    <th>Order ID</th>
                                    <th>Jumlah</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($orders as $order)
                                    <tr>
                                        <td>{{ $order->created_at->format('d M Y H:i') }}</td>
                                        <td>
                                            @if ($order->course)
                                                <a href="{{ route('user.courses.show', $order->course->slug) }}">
                                                    {{ $order->course->title }}
                                                </a>
                                            @else
                                                <span class="text-muted">Kursus tidak ditemukan</span>
                                            @endif
                                        </td>
                                        <td><code>{{ $order->reference_id }}</code></td>
                                        <td>Rp {{ number_format($order->total_amount, 0, ',', '.') }}</td>
                                        <td>
                                            @if ($order->payment_status === 'settlement')
                                                <span class="badge bg-success">Lunas</span>
                                            @elseif($order->payment_status === 'pending')
                                                <span class="badge bg-warning">Pending</span>
                                            @elseif($order->payment_status === 'failed')
                                                <span class="badge bg-danger">Gagal</span>
                                            @else
                                                <span
                                                    class="badge bg-secondary">{{ ucfirst($order->payment_status) }}</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if ($order->payment_status === 'settlement' && $order->course)
                                                <a href="{{ route('user.courses.show', $order->course->slug) }}"
                                                    class="btn btn-sm btn-primary">
                                                    <i class="fa-solid fa-book"></i> Lihat Kursus
                                                </a>
                                            @elseif($order->payment_status === 'pending' && $order->snap_token)
                                                <button class="btn btn-sm btn-warning"
                                                    onclick="payAgain('{{ $order->snap_token }}')">
                                                    <i class="fa-solid fa-credit-card"></i> Bayar
                                                </button>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4">
                        {{ $orders->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>

    @push('scripts')
        <script type="text/javascript" src="https://app.sandbox.midtrans.com/snap/snap.js"
            data-client-key="{{ config('services.midtrans.clientKey') }}"></script>
        <script>
            function payAgain(snapToken) {
                snap.pay(snapToken, {
                    onSuccess: function(result) {
                        window.location.href = "{{ route('user.payment.success') }}?order_id=" + result.order_id;
                    },
                    onPending: function(result) {
                        Swal.fire('Pending', 'Pembayaran Anda sedang diproses', 'info');
                    },
                    onError: function(result) {
                        Swal.fire('Error', 'Terjadi kesalahan saat memproses pembayaran', 'error');
                    },
                    onClose: function() {
                        console.log('Payment popup closed');
                    }
                });
            }
        </script>
    @endpush
@endsection
