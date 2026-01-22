@extends('layouts.admin')
@section('title', 'Admin Internal')
@section('header', 'Data Admin Internal')

@section('content')
<div class="card">
    <div class="card-header">
        <a href="{{ route('superadmin.admin.create') }}" class="btn btn-primary">Tambah Admin Internal</a>
    </div>
    <div class="card-body">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($admins as $i => $u)
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td>{{ $u->full_name }}</td>
                    <td>{{ $u->email }}</td>
                    <td>{{ $u->role }}</td>
                    <td>
                        <a href="{{ route('superadmin.admin.edit', $u->id) }}" class="btn btn-warning btn-sm">Edit</a>
                        <form action="{{ route('superadmin.admin.destroy', $u->id) }}" method="POST" class="d-inline">
                            @csrf @method('DELETE')
                            <button class="btn btn-danger btn-sm" onclick="return confirm('Yakin?')">Hapus</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
