<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\superadmin\Superadmincontroller;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\OtpController;
use App\Http\Middleware\CekUserIsActive;
use Illuminate\Support\Facades\Auth;
use App\Http\Middleware\RedirectIfAuthenticated;
use App\Http\Middleware\CheckRole;
use App\Http\Middleware\EnsureUserIsActive;

// --- GROUP GUEST (Login/Register) ---
Route::middleware(['guest'])->group(function () {
    Route::get('/login', [AuthController::class, 'login'])->name('login');
    Route::post('/login', [AuthController::class, 'loginPost']);
    Route::get('/register', [AuthController::class, 'register']);
    Route::post('/register', [AuthController::class, 'registerPost'])->name('register');
});

// --- LOGOUT ---
Route::post('/logout', [AuthController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');

// --- HALAMAN OTP (Hanya butuh Login, TIDAK butuh status Active)
Route::middleware(['auth'])->group(function() {
    Route::get('/verify-otp', [OtpController::class, 'index'])->name('otp.verification');
    Route::post('/verify-otp', [OtpController::class, 'verify'])->name('otp.check');
});

Route::middleware(['auth'])->group(function() {
    Route::get('/verify-otp', [OtpController::class, 'index'])->name('otp.verification');
    Route::post('/verify-otp', [OtpController::class, 'verify'])->name('otp.check');
    Route::post('/resend-otp', [OtpController::class, 'resend'])->name('otp.resend');

    Route::get('/change-password', [AuthController::class, 'changePasswordView'])->name('password.change');
    Route::post('/change-password/send', [AuthController::class, 'sendChangePasswordOtp'])->name('password.sendOtp');
    Route::post('/change-password/update', [AuthController::class, 'updatePassword'])->name('password.update');
});


// super admin
Route::middleware(['auth', 'role:super_admin', CekUserIsActive::class]) // <--- Pasang Satpam Status Disini
    ->prefix('superadmin')
    ->name('superadmin.')
    ->group(function () {
        Route::get('/dashboard', function () {
            return view('superadmin.dashboard');
        })->name('dashboard');

        Route::resource('admins', Superadmincontroller::class);
        
    });

// student
    Route::middleware(['auth', 'role:student', CekUserIsActive::class]) // <--- Pasang Satpam Status Disini
        ->prefix('student')
        ->name('student.')
        ->group(function () {
            Route::get('/dashboard', function () {
                return view('auth.change-password');
            })->name('dashboard');

        Route::resource('profiles', UserController::class)->only(['show', 'edit', 'update']);
    });

// admin
Route::middleware(['auth', 'role:admin', CekUserIsActive::class]) // <--- Pasang Satpam Status Disini
    ->name('admin.')
    ->group(function () {
        Route::get('/dashboard-admin', function () {
            return 'Halaman admin biasa';
        })->name('dashboard');
    });

// school admin
Route::middleware(['auth', 'role:school_admin', CekUserIsActive::class]) // <--- Pasang Satpam Status Disini
    ->name('school_admin.') // <--- Koreksi: Tambahkan titik (.) agar rapi (school_admin.dashboard)
    ->group(function () {
        Route::get('/dashboard-sekolah', function () {
            return 'Halaman admin sekolah';
        })->name('dashboard');
    });
