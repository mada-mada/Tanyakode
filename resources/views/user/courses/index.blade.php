@extends('layouts.user')

@section('content')
<div class="container-fluid">
    <h3 class="mb-4">Daftar Kursus Tersedia</h3>
    <div class="row">
        @foreach($courses as $course)
        <div class="col-md-4">
            <div class="card mb-4 shadow-sm">
                <img src="{{ $course->thumbnail_url ?? 'https://via.placeholder.com/300x150' }}" class="card-img-top" alt="{{ $course->title }}">
                <div class="card-body">
                    <h5 class="card-title">{{ $course->title }}</h5>
                    <p class="card-text text-muted">{{ Str::limit($course->description, 100) }}</p>
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="badge badge-success">Rp {{ number_format($course->price, 0, ',', '.') }}</span>
                        <a href="{{ route('user.courses.show', $course->slug) }}" class="btn btn-sm btn-primary">Lihat Detail</a>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endsection