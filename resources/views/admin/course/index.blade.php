@extends('adminlte::page')

@section('title', 'Daftar Kursus')

@section('content_header')
    <h1>Manajemen Kursus</h1>
@stop

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">List Kursus Saya</h3>
        <div class="card-tools">
            <a href="{{ route('courses.create') }}" class="btn btn-primary btn-sm">
                <i class="fas fa-plus"></i> Buat Kursus Baru
            </a>
        </div>
    </div>
    <div class="card-body p-0">
        <table class="table table-striped projects">
            <thead>
                <tr>
                    <th>Judul Kursus</th>
                    <th>Level</th>
                    <th>Harga</th>
                    <th>Status</th>
                    <th style="width: 20%">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($courses as $course)
                <tr>
                    <td>
                        <a>{{ $course->title }}</a>
                        <br/>
                        <small>Dibuat: {{ $course->created_at->format('d M Y') }}</small>
                    </td>
                    <td>
                        <span class="badge badge-{{ $course->level == 'pemula' ? 'success' : ($course->level == 'menengah' ? 'warning' : 'danger') }}">
                            {{ ucfirst($course->level) }}
                        </span>
                    </td>
                    <td>
                        {{ $course->price > 0 ? 'Rp ' . number_format($course->price, 0, ',', '.') : 'Gratis' }}
                    </td>
                    <td>
                        <span class="badge badge-{{ $course->is_premium ? 'secondary' : 'success' }}">
                            {{ $course->is_premium ? 'Premium' : 'Free' }}
                        </span>
                    </td>
                    <td class="project-actions">
                        <a class="btn btn-primary btn-sm" href="{{ route('courses.show', $course->id) }}">
                            <i class="fas fa-folder"></i> Kelola Materi
                        </a>
                        <a class="btn btn-info btn-sm" href="{{ route('courses.edit', $course->id) }}">
                            <i class="fas fa-pencil-alt"></i>
                        </a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@stop
