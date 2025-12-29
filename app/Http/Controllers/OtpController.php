<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Otp;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Mail\OtpMail;

class OtpController extends Controller
{
    /**

     */
    public function index()
    {
        return view('auth.otp');
    }

    /**
     * proses Logika Pengecekan Kode OTP
     */
    public function verify(Request $request)
    {
        $request->validate([
            'otp_code' => 'required|numeric|digits:6'
        ], [
            'otp_code.required' => 'Kode OTP wajib diisi.',
            'otp_code.numeric'  => 'Kode OTP harus berupa angka.',
            'otp_code.digits'   => 'Kode OTP harus 6 digit.'
        ]);

        $user = Auth::user();

        //Cari Kode OTP di Database milik user ini
        $otpRecord = Otp::where('user_id', $user->id)
                        ->where('otp_code', $request->otp_code)
                        ->first();


        if (!$otpRecord) {
            return back()->with('error', 'Kode OTP salah atau tidak ditemukan.');
        }

        if (Carbon::now()->gt($otpRecord->expires_at)) {
            return back()->with('error', 'Kode OTP sudah kadaluarsa. Silakan minta ulang.');
        }


        //Update Status User Menjadi 'active'
        $userUpdate = User::find($user->id);
        $userUpdate->status = 'active';
        $userUpdate->save();

        //Hapus Data OTP
        $otpRecord->delete();

        // Redirect ke Dashboard Sesuai Role
        switch ($user->role) {
            case 'super_admin':
                return redirect()->route('superadmin.dashboard');

            case 'student':
                return redirect()->route('student.dashboard');

            case 'admin':
                return redirect()->route('admin.dashboard');

            case 'school_admin':
                return redirect()->route('school_admin.dashboard');

            default:
                return redirect('/');
        }
    }
    /**
     * Mengirim Ulang Kode OTP
     */
public function resend()
{
    $user = Auth::user();

    //Generate Kode Baru
    $otpCode = rand(100000, 999999);

    //Hapus OTP lama & Simpan baru
    Otp::where('user_id', $user->id)->delete();

    Otp::create([
        'user_id' => $user->id,
        'otp_code' => $otpCode,
        'expires_at' => Carbon::now()->addMinutes(5)
    ]);

    //Kirim Email Ulang
    try {
        Mail::to($user->email)->send(new OtpMail($user, $otpCode));
        return back()->with('success', 'Kode OTP baru telah dikirim ulang.');
    } catch (\Exception $e) {
        return back()->with('error', 'Gagal mengirim email. Cek koneksi internet.');
    }
}

}
