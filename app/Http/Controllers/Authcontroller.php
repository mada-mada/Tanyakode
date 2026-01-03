<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Otp;
use App\Mail\OtpMail;

class AuthController extends Controller
{
    // --- LOGIN PAGE (GET) ---
    public function login()
    {
        // 1. Cek apakah user sudah login?
        if (Auth::check()) {
            $user = Auth::user();

            // [LOGIKA PENTING] Cek Status DULU sebelum cek Role
            // Jika user login tapi status masih 'verify', tendang ke halaman OTP
            if ($user->status === 'verify') {
                return redirect()->route('otp.verification');
            }

            // Jika status active, baru cek role
            if ($user->role === 'super_admin') return redirect()->route('superadmin.dashboard');
            if ($user->role === 'student') return redirect()->route('student.dashboard');
            if ($user->role === 'admin') return redirect()->route('admin.dashboard');
            if ($user->role === 'school_admin') return redirect()->route('school_admin.dashboard');

            return redirect('/');
        }

        // 2. Tampilkan Halaman Login dengan Header Anti-Cache
        // Ini ditaruh DISINI, bukan di loginPost.
        return response()
            ->view('auth.login')
            ->header('Cache-Control', 'no-cache, no-store, must-revalidate')
            ->header('Pragma', 'no-cache')
            ->header('Expires', '0');
    }

    // --- PROSES LOGIN (POST) ---
    public function loginPost(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            $user = Auth::user();

            // [LOGIKA PENTING] Cek Status lagi setelah berhasil login
            if ($user->status === 'verify') {
                return redirect()->route('otp.verification');
            }

            // Redirect sesuai Role (Gunakan route name agar konsisten)
            switch ($user->role) {
                case 'super_admin':
                    return redirect()->route('superadmin.dashboard');
                case 'admin':
                    return redirect()->route('admin.dashboard');
                case 'student':
                    return redirect()->route('student.dashboard');
                case 'school_admin':
                    return redirect()->route('school_admin.dashboard');
                default:
                    return redirect('/');
            }
        }

        // Jika login gagal
        return back()->withErrors([
            'email' => 'Email atau password salah.',
        ])->onlyInput('email');
    }

    // --- REGISTER PAGE (GET) ---
    public function register()
    {
        return view('auth.register');
    }

    // --- PROSES REGISTER (POST) ---
   public function registerPost(Request $request)
{
    // 1. Validasi Input Lengkap
    $request->validate([
        'username'      => 'required|unique:users',
        'email'         => 'required|email|unique:users',
        'password'      => 'required|min:6|confirmed', // confirmed utk cek password_confirmation
    ]);

    // 2. Buat User (Masukkan semua field dari form)
    $user = User::create([
        'username' => $request->username,
        'email'    => $request->email,
        'password' => Hash::make($request->password),
        'role'     => 'student',
        'status'   => 'verify', // Status awal verify agar kena middleware OTP
        'current_level'   => 0, // Default levelx
    ]);

    // 3. Panggil Fungsi Kirim OTP (Sama seperti sebelumnya)
    $this->sendOtp($user);

    // 4. Login Otomatis
    Auth::login($user);

    // 5. Redirect ke halaman Input OTP
    return redirect()->route('otp.verification');
}
    // --- FUNGSI KIRIM OTP (HELPER) ---
    public function sendOtp($user)
    {
        $otpCode = rand(100000, 999999);

        Otp::where('user_id', $user->id)->delete();

        Otp::create([
            'user_id' => $user->id,
            'otp_code' => $otpCode,
            'expires_at' => Carbon::now()->addMinutes(5)
        ]);

        try {
            Mail::to($user->email)->send(new OtpMail($user, $otpCode));
        } catch (\Exception $e) {
            // Log error jika perlu: \Log::error($e->getMessage());
        }
    }

    // --- LOGOUT ---
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login')->with('success', 'Anda berhasil logout.');
    }

    // 1. Tampilkan Halaman Ganti Password
    public function changePasswordView()
    {
        return view('auth.change-password');
    }

    // 2. Kirim OTP untuk Ganti Password
    public function sendChangePasswordOtp()
    {
        $user = Auth::user();

        // Panggil fungsi sendOtp yang sudah kita buat sebelumnya
        $this->sendOtp($user);

        // Beri tanda di session bahwa user sudah minta OTP
        // Tanda ini dipakai di View untuk memunculkan form input password
        Session::flash('otp_sent', true);
        Session::flash('success', 'Kode OTP telah dikirim ke email Anda.');

        return redirect()->route('password.change');
    }

    // 3. Proses Update Password
    public function updatePassword(Request $request)
    {
        // Validasi Input
        $request->validate([
            'otp_code' => 'required|numeric',
            'password' => 'required|min:6|confirmed', // Password baru & Konfirmasi
        ]);

        $user = Auth::user();

        // Cek OTP di Database
        $otpRecord = Otp::where('user_id', $user->id)
                        ->where('otp_code', $request->otp_code)
                        ->first();

        // Validasi OTP
        if (!$otpRecord) {
            Session::flash('otp_sent', true); // Biar form tidak hilang
            return back()->with('error', 'Kode OTP salah!');
        }
        if (Carbon::now()->gt($otpRecord->expires_at)) {
            Session::flash('otp_sent', true);
            return back()->with('error', 'Kode OTP kadaluarsa!');
        }

        // JIKA SUKSES:
        // 1. Update Password User
        $userUpdate = User::find($user->id);
        $userUpdate->password = Hash::make($request->password);
        $userUpdate->save();

        // 2. Hapus OTP
        $otpRecord->delete();

        // 3. Kembali dengan pesan sukses
        return redirect()->route('password.change')->with('success', 'Password berhasil diubah!');
    }
}

