<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AuthController extends Controller
{
    public function login()
    {
        return view('auth.login');
    }

    public function loginPost(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);


        if (Auth::attempt($credentials)) {

            $request->session()->regenerate();
            $user = Auth::user();

            switch ($user->role) {
                case 'super_admin':
                    return redirect('superadmin/dashboard');

                case 'admin':
                    return redirect('admin/dashboard');

                case 'student':
                    return redirect('student/dashboard');

                case 'school_admin':
                    return redirect('admin/dashboard');

                default:
                    return redirect('dashboard');
            }
        }


        return back()->withErrors([
            'email' => 'Email atau password salah.',
        ])->onlyInput('email');
    }


    public function register()
    {
        return view('auth.register');
    }

    public function registerPost(Request $request)
    {

        $request->validate([

            'username'        => 'required|string|max:50|unique:users,username',
            'email'           => 'required|email|max:100|unique:users,email',
            'password'        => 'required|min:6|confirmed',

            'full_name'       => 'required|string|max:100',
            'nis'             => 'required|numeric',
            'nisn'            => 'required|numeric|unique:users,nisn',
            'grade'           => 'required|in:1,2,3',
            'school_name'     => 'required|string|max:100',
            'school_category' => 'required|in:SMP,SMA,',
            'domisili'        => 'required|string|max:100',
        ]);

        $user = User::create([
            'username'        => $request->username,
            'email'           => $request->email,
            'password'        => Hash::make($request->password), // Password di-hash
            'full_name'       => $request->full_name,


            'nis'             => $request->nis,
            'nisn'            => $request->nisn,
            'grade'           => $request->grade,
            'school_name'     => $request->school_name,
            'school_category' => $request->school_category,
            'domisili'        => $request->domisili,


            'role'            => 'student',
            'current_level'   => 1,
            'school_id'       => null,
            'avatar_url'      => null,
        ]);


        Auth::login($user);


        return redirect('student/dashboard')->with('success', 'Registrasi berhasil! Selamat datang.');
    }

}

?>
