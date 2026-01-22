<?php

namespace App\Http\Controllers\user;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use App\Models\User;
use App\Models\Course;
use App\Models\School;

class UserController extends Controller
{
    /**
     * Menampilkan halaman profil (View Blade)
     */
    public function show(Request $request)
    {
        $user = Auth::user();
        $user->load('school'); // Memuat relasi sekolah

        return view('user.profiles.show', compact('user'));
    }

    /**
     * Menampilkan halaman edit profil
     */
    public function edit()
    {
        $user = Auth::user();
        
        return view('user.profiles.edit', compact('user'));
    }

    /**
     * Memproses update profil dari form
     */
    public function update(Request $request)
    {
        $student = Auth::user();

        // Validasi data sesuai dengan field di edit.blade.php dan struktur tabel users
        $request->validate([
            'full_name'       => 'required|string|max:100',
            'domisili'        => 'nullable|string|max:100',
            'grade'           => ['nullable', Rule::in(['1', '2', '3'])], // Validasi enum sesuai DB
            'school_category' => ['nullable', Rule::in(['SMP', 'SMA'])], // Validasi enum sesuai DB
            'username'        => ['required', 'string', 'max:50', Rule::unique('users')->ignore($student->id)],
            'email'           => ['required', 'email', 'max:100', Rule::unique('users')->ignore($student->id)],
            'nis'             => ['nullable', 'string', Rule::unique('users')->ignore($student->id)],
            'nisn'            => ['nullable', 'string', Rule::unique('users')->ignore($student->id)],
            'password'        => 'nullable|string|min:8',
            'avatar'          => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        // Mengisi data objek student dengan data dari request
        $student->full_name       = $request->full_name;
        $student->username        = $request->username;
        $student->email           = $request->email;
        $student->domisili        = $request->domisili;
        $student->grade           = $request->grade;
        $student->school_category = $request->school_category;
        $student->nis             = $request->nis;
        $student->nisn            = $request->nisn;

        // Hash password jika kolom diisi
        if ($request->filled('password')) {
            $student->password = Hash::make($request->password);
        }

        // Logika upload avatar
        if ($request->hasFile('avatar')) {
            // Hapus file lama di storage jika ada
            if ($student->avatar_url && Storage::disk('public')->exists($student->avatar_url)) {
                Storage::disk('public')->delete($student->avatar_url);
            }
            
            // Simpan file baru ke folder avatars/students
            $path = $request->file('avatar')->store('avatars/students', 'public');
            $student->avatar_url = $path;
        }

        $student->save();

        // Alihkan ke halaman show profil dengan pesan sukses
        return redirect()->route('user.profiles.show')->with('success', 'Profil Anda berhasil diperbarui.');
    }

    /**
     * Halaman Belajar
     */
    public function learning($slug)
    {
        $course = Course::where('slug', $slug)->firstOrFail();

        if ($course->price > 0 && !auth()->user()->hasPurchased($course->id)) {
            return redirect()->route('user.courses.show', $course->slug)
                ->with('error', 'Silakan beli kursus ini terlebih dahulu.');
        }

        return view('user.courses.learning', compact('course'));
    }

    /**
     * Bergabung ke Sekolah menggunakan Token
     */
    public function joinSchool(Request $request) 
    {
        $request->validate([
            'token_code' => 'required|string'
        ]);

        $school = School::where('token_code', $request->token_code)
                        ->where('is_token_active', 1)
                        ->first();

        if (!$school) {
            return back()->with('error', 'Kode sekolah tidak valid atau sudah tidak aktif.');
        }

        $user = Auth::user();
        $user->update([
            'school_id' => $school->id
        ]);

        return back()->with('success', 'Berhasil bergabung dengan ' . $school->name);
    }
}