@extends('layouts.app')

@section('content')
<div class="container">
    <h3>Profil Saya</h3>

    <p><b>Nama:</b> {{ $user->nama_lengkap ?? '-' }}</p>
    <p><b>Email:</b> {{ $user->email }}</p>
    <p><b>Sekolah:</b> {{ $user->sekolah ?? '-' }}</p>
    <p><b>Alamat:</b> {{ $user->alamat ?? '-' }}</p>
    <p><b>No HP:</b> {{ $user->no_hp ?? '-' }}</p>

    <a href="{{ route('user.profile.edit') }}" class="btn btn-primary">
        Edit Profil
    </a>
</div>
@endsection