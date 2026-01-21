<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Otp;
use App\Mail\OtpMail;

class AuthController extends Controller
{
    // --- AUTH DASAR (Login/Register/Logout) ---
    
    public function login() {
        if (Auth::check()) return redirect('/'); 
        // Redirect user jika sudah login (bisa disesuaikan role)
        return view('Auth.login');
    }

    public function loginPost(Request $request) {
        $credentials = $request->validate(['email' => 'required|email', 'password' => 'required']);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            $user = Auth::user();
            if ($user->status === 'verify') return redirect()->route('otp.verification');
            
            // Redirect sesuai role
            if ($user->role === 'super_admin') return redirect()->route('superadmin.dashboard');
            if ($user->role === 'student') return redirect()->route('user.dashboard');
            if ($user->role === 'admin') return redirect()->route('admin.dashboard');
            if ($user->role === 'school_admin') return redirect()->route('school_admin.dashboard');
            return redirect('/');
        }
        return back()->withErrors(['email' => 'Email atau password salah.']);
    }

    public function register() {
        return view('Auth.register');
    }

    public function registerPost(Request $request) {
        $request->validate([
            'username' => 'required|unique:users',
            'email'    => 'required|email|unique:users',
            'password' => 'required|min:6|confirmed',
        ]);
        $user = User::create([
            'username' => $request->username,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'role'     => 'student',
            'status'   => 'verify',
            'current_level' => 0
        ]);
        $this->sendOtp($user);
        Auth::login($user);
        return redirect()->route('otp.verification');
    }

    public function logout(Request $request) {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login')->with('success', 'Anda berhasil logout.');
    }

    // ==========================================
    //      FITUR LUPA PASSWORD (GUEST FLOW)
    // ==========================================

    // 1. Tampilkan Halaman Input Email
    public function showForgotPasswordForm()
    {
        return view('Auth.forgot-password');
    }

    // 2. Proses Kirim OTP (Guest)
    public function sendForgotPasswordOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email'
        ], [
            'email.exists' => 'Email tidak ditemukan dalam sistem kami.'
        ]);

        $user = User::where('email', $request->email)->first();

        // Simpan email di Session karena user belum login
        Session::put('reset_email', $user->email);
        
        // Kirim OTP
        $this->sendOtp($user);

        // Redirect ke halaman OTP khusus Reset Password
        return redirect()->route('password.forgot.otp')->with('success', 'Kode OTP telah dikirim ke email Anda.');
    }

    // 3. Tampilkan Halaman OTP (Guest)
   public function showForgotOtpView()
{
    // Cek apakah session email ada? Jika tidak, tendang balik ke input email
    if (!Session::has('reset_email')) {
        return redirect()->route('password.forgot')->with('error', 'Sesi habis, silakan masukkan email kembali.');
    }

    return view('Auth.otp', [
        'title'     => 'Reset Password',
        'message'   => 'Masukkan kode OTP untuk mereset password Anda.',
        'actionUrl' => route('password.forgot.verify')
    ]);
}

    // 4. Verifikasi OTP (Guest)
    public function verifyForgotOtp(Request $request)
    {
        if (!Session::has('reset_email')) return redirect()->route('password.forgot');

        $request->validate(['otp_code' => 'required|numeric']);
        
        $email = Session::get('reset_email');
        $user = User::where('email', $email)->first();
        $otpRecord = Otp::where('user_id', $user->id)->where('otp_code', $request->otp_code)->first();

        if (!$otpRecord) return back()->with('error', 'Kode OTP salah!');
        if (Carbon::now()->gt($otpRecord->expires_at)) return back()->with('error', 'Kode OTP kadaluarsa!');

        // OTP Valid! Hapus OTP dan beri "Tiket" akses halaman reset
        $otpRecord->delete();
        Session::put('allow_reset_password', true); 

        return redirect()->route('password.reset.form');
    }

    // 5. Tampilkan Form Reset Password (Guest)
    public function showResetPasswordForm()
    {
        if (!Session::has('allow_reset_password')) return redirect()->route('password.forgot');

        // Reuse view change-password dengan actionUrl reset
        return view('Auth.change-password', [
            'actionUrl' => route('password.reset.update')
        ]);
    }

    // 6. Update Password Baru ke DB (Guest)
    public function updateResetPassword(Request $request)
    {
        if (!Session::has('allow_reset_password')) return redirect()->route('login');

        $request->validate(['password' => 'required|min:6|confirmed']);

        $email = Session::get('reset_email');
        $user = User::where('email', $email)->first();

        $user->password = Hash::make($request->password);
        $user->save();

        Session::forget(['reset_email', 'allow_reset_password']);

        return redirect()->route('login')->with('success', 'Password berhasil direset! Silakan login.');
    }


    // ==========================================
    //      LOGIKA GANTI PASSWORD (AUTH USER)
    // ==========================================

    public function changePasswordView() {
        return view('Auth.change-password');
    }

    public function requestChangePassword(Request $request) {
        $request->validate(['password' => 'required|min:6|confirmed']);
        $user = Auth::user();
        
        Session::put('temp_new_password', Hash::make($request->password));
        $this->sendOtp($user);

        return redirect()->route('password.change.otp');
    }

    public function showChangePasswordOtpView() {
        if (!Session::has('temp_new_password')) return redirect()->route('password.change');
        
        return view('Auth.otp', [
            'actionUrl' => route('password.verify'),
            'title' => 'Konfirmasi Ganti Password',
            'message' => 'Demi keamanan, masukkan kode OTP untuk konfirmasi.'
        ]);
    }

    public function verifyAndChangePassword(Request $request) {
        if (!Session::has('temp_new_password')) return redirect()->route('password.change');
        $request->validate(['otp_code' => 'required|numeric']);

        $user = Auth::user();
        $otpRecord = Otp::where('user_id', $user->id)->where('otp_code', $request->otp_code)->first();

        if (!$otpRecord) return back()->with('error', 'Kode OTP salah!');
        if (Carbon::now()->gt($otpRecord->expires_at)) return back()->with('error', 'Kode OTP kadaluarsa!');

        $userUpdate = User::find($user->id);
        $userUpdate->password = Session::get('temp_new_password');
        $userUpdate->save();

        $otpRecord->delete();
        Session::forget('temp_new_password');

        return redirect()->route('password.change')->with('success', 'Password berhasil diperbarui!');
    }
    
    public function cancelChangePassword() {
        Session::forget(['temp_new_password']);
        return redirect()->route('password.change');
    }

    // HELPER OTP
    public function sendOtp($user) {
        $otpCode = rand(100000, 999999);
        Otp::where('user_id', $user->id)->delete();
        Otp::create(['user_id' => $user->id, 'otp_code' => $otpCode, 'expires_at' => Carbon::now()->addMinutes(5)]);
        try { Mail::to($user->email)->send(new OtpMail($user, $otpCode)); } catch (\Exception $e) { Log::error($e->getMessage()); }
    }
}