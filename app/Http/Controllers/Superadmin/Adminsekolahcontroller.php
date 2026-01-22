<?php

namespace App\Http\Controllers\superadmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\School;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class Adminsekolahcontroller extends Controller
{
    public function index()
    {
        // Mengambil user role school_admin beserta data sekolahnya
        $school_admin = User::where('role', 'school_admin')->with('school')->get();
        return view('superadmin.adminsekolah.index', compact('school_admin'));
    }

    public function create()
    {
        $sekolah = School::all();
        return view('superadmin.adminsekolah.create', compact('sekolah'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'full_name' => 'required|string',
            'username' => 'required|unique:users',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
            'school_id' => 'required|exists:schools,id',
        ]);

       User::create([
             'full_name' => $request->full_name,
             'username' => $request->username,
             'email' => $request->email,
             'password' => $request->password, // Biarkan Model yang melakukan hashing otomatis
             'role' => 'school_admin',
             'school_id' => $request->school_id,
        ]);

        // PERBAIKAN: Gunakan 'superadmin.adminsekolah.index'
        return redirect()->route('superadmin.adminsekolah.index')->with('success', 'Admin Sekolah berhasil dibuat');
    }

    public function edit($id)
    {
        $adminsekolah = User::findOrFail($id);
        $sekolah = School::all();
        return view('superadmin.adminsekolah.edit', compact('adminsekolah', 'sekolah'));
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'full_name' => 'required',
            'username' => ['required', Rule::unique('users')->ignore($user->id)],
            'email' => ['required', 'email', Rule::unique('users')->ignore($user->id)],
            'school_id' => 'required|exists:schools,id',
        ]);

        $data = [
            'full_name' => $request->full_name,
            'username' => $request->username,
            'email' => $request->email,
            'school_id' => $request->school_id,
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        // PERBAIKAN: Gunakan 'superadmin.adminsekolah.index'
        return redirect()->route('superadmin.adminsekolah.index')->with('success', 'Data berhasil diupdate');
    }

    public function destroy($id)
    {
        User::findOrFail($id)->delete();
        // PERBAIKAN: Gunakan 'superadmin.adminsekolah.index'
        return redirect()->route('superadmin.adminsekolah.index')->with('success', 'User berhasil dihapus');
    }
}
