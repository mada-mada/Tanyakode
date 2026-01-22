@extends('layouts.admin')
@section('title', 'Admin Sekolah')
@section('header', 'Kelola Admin Sekolah')

@section('content')
<div class="card">
    <div class="card-header">
        <a href="{{ route('superadmin.adminsekolah.create') }}" class="btn btn-primary"><i class="fas fa-plus"></i> Tambah Admin</a>
    </div>
    <div class="card-body">
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Admin</th>
                    <th>Username</th>
                    <th>Email</th>
                    <th>Asal Sekolah</th> <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($school_admin as $index => $user)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $user->full_name }}</td>
                    <td>{{ $user->username }}</td>
                    <td>{{ $user->email }}</td>

                    <td>
                        @if($user->school)
                            {{ $user->school->name }}
                        @else
                            <span class="text-danger">Tidak ada sekolah</span>
                        @endif
                    </td>

                    <td>
                        <a href="{{ route('superadmin.adminsekolah.edit', $user->id) }}" class="btn btn-warning btn-sm">Edit</a>
                        <form action="{{ route('superadmin.adminsekolah.destroy', $user->id) }}" method="POST" class="d-inline">
                            @csrf @method('DELETE')
                            <button class="btn btn-danger btn-sm" onclick="return confirm('Hapus user ini?')">Hapus</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
