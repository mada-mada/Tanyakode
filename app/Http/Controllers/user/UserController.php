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
        $user->load('school'); // Memuat data sekolah jika ada

        // Mengarah ke resources/views/user/profiles/show.blade.php
        return view('user.profiles.show', compact('user'));
    }

    /**
     * Memproses update profil dari form
     */
    public function update(Request $request)
    {
        $student = Auth::user();

        $request->validate([
            'full_name' => 'required|string|max:100',
            'domisili'  => 'nullable|string|max:100',
            'grade'     => ['nullable', Rule::in(['1', '2', '3'])],
            'school_category' => ['nullable', Rule::in(['SMP', 'SMA'])],
            'username'  => ['required', 'string', 'max:50', Rule::unique('users')->ignore($student->id)],
            'email'     => ['required', 'email', 'max:100', Rule::unique('users')->ignore($student->id)],
            'nis'       => ['nullable', 'string', Rule::unique('users')->ignore($student->id)],
            'nisn'      => ['nullable', 'string', Rule::unique('users')->ignore($student->id)],
            'password'  => 'nullable|string|min:8',
            'avatar'    => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $student->full_name       = $request->full_name;
        $student->username        = $request->username;
        $student->email           = $request->email;
        $student->domisili        = $request->domisili;
        $student->grade           = $request->grade;
        $student->school_category = $request->school_category;
        $student->nis             = $request->nis;
        $student->nisn            = $request->nisn;

        if ($request->filled('password')) {
            $student->password = Hash::make($request->password);
        }

        if ($request->hasFile('avatar')) {
            if ($student->avatar_url && Storage::disk('public')->exists($student->avatar_url)) {
                Storage::disk('public')->delete($student->avatar_url);
            }
            $path = $request->file('avatar')->store('avatars/students', 'public');
            $student->avatar_url = $path;
        }

        $student->save();

        // Mengalihkan kembali dengan pesan sukses
        return redirect()->back()->with('success', 'Profil Anda berhasil diperbarui.');
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

    public function edit()
{
    $user = Auth::user(); // Ambil data user yang login
    
    // Pastikan path view sesuai dengan nama folder Anda (user.profiles.edit)
    return view('user.profiles.edit', compact('user'));
}
}