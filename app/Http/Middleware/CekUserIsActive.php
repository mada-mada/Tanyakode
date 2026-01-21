<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CekUserIsActive
{
    public function handle(Request $request, Closure $next): Response
    {
        // 1. Cek apakah user sudah login
        if (Auth::check()) {

            // 2. Ambil status user
            $status = Auth::user()->status;

            // 3. JIKA status masih 'verify', LEMPAR ke halaman verifikasi OTP
            if ($status === 'verify') {
                return redirect()->route('otp.verification');
            }
        }

        // Jika status 'active', silakan lewat
        return $next($request);
    }
}
