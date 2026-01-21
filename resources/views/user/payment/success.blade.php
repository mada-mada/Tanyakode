@extends('layouts.user')

@section('content')
<div class="container text-center mt-5">
    <div class="card">
        <div class="card-body">
            <h1 class="text-success"><i class="fas fa-check-circle"></i></h1>
            <h3 class="mt-3">Pembayaran Berhasil!</h3>
            <p>Terima kasih telah membeli kursus ini. Silakan cek menu "Kursus Saya" untuk mulai belajar.</p>
            <a href="{{ route('courses.index') }}" class="btn btn-primary">Kembali ke Katalog</a>
        </div>
    </div>
</div>
@endsection