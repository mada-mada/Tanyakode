@extends('layouts.user')

@section('title', 'Notifikasi')

@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2>Notifikasi</h2>
                <button class="btn btn-sm btn-outline-primary" onclick="markAllRead()">
                    <i class="fa-solid fa-check-double"></i> Tandai Semua Dibaca
                </button>
            </div>
            
            @if($notifications->isEmpty())
                <div class="alert alert-info">
                    <i class="fa-solid fa-bell-slash"></i> Belum ada notifikasi.
                </div>
            @else
                <div class="list-group">
                    @foreach($notifications as $notification)
                        @php
                            $data = json_decode($notification->data, true);
                            $isRead = !is_null($notification->read_at);
                        @endphp
                        <div class="list-group-item {{ $isRead ? '' : 'list-group-item-primary' }}">
                            <div class="d-flex w-100 justify-content-between">
                                <h5 class="mb-1">
                                    @if(!$isRead)
                                        <span class="badge bg-primary">Baru</span>
                                    @endif
                                    {{ $data['title'] ?? 'Notifikasi' }}
                                </h5>
                                <small class="text-muted">{{ \Carbon\Carbon::parse($notification->created_at)->diffForHumans() }}</small>
                            </div>
                            <p class="mb-1">{{ $data['message'] ?? 'Tidak ada pesan' }}</p>
                            
                            @if(isset($data['action_url']))
                                <a href="{{ $data['action_url'] }}" class="btn btn-sm btn-primary mt-2">
                                    <i class="fa-solid fa-arrow-right"></i> Lihat Detail
                                </a>
                            @endif
                        </div>
                    @endforeach
                </div>
                
                <div class="mt-4">
                    {{ $notifications->links() }}
                </div>
            @endif
        </div>
    </div>
</div>

@push('scripts')
<script>
function markAllRead() {
    fetch('/notifications/read-all', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Content-Type': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === 'success') {
            Swal.fire({
                icon: 'success',
                title: 'Berhasil',
                text: 'Semua notifikasi telah ditandai sebagai dibaca',
                timer: 2000,
                showConfirmButton: false
            }).then(() => {
                location.reload();
            });
        }
    })
    .catch(error => {
        Swal.fire('Gagal', 'Terjadi kesalahan', 'error');
    });
}
</script>
@endpush
@endsection
