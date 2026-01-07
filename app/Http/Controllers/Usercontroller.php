<?php

namespace App\Http\Controllers;
use Laravel\Sanctum\HasApiTokens;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use App\Models\User;

class UserController extends Controller
{
    /**

     */
    public function show(Request $request)
    {
        $student = $request->user();
        $student->load('school');

        return response()->json([
            'status' => true,
            'message' => 'Detail Profil Siswa',
            'data' => $student
        ], 200);
    }

    /**

     */
    public function update(Request $request)
    {
        $student = $request->user();


        $validator = $request->validate([

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

        return response()->json([
            'status' => true,
            'message' => 'Profil berhasil diperbarui',
            'data' => $student
        ], 200);
    }
}
