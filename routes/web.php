<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

// Import Controllers
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\OtpController;
use App\Http\Controllers\superadmin\Superadmincontroller;
use App\Http\Controllers\superadmin\Adminsekolahcontroller;
use App\Http\Controllers\superadmin\Superadmin_sekolahcontroller;
use App\Http\Controllers\User\PaymentController;

// Import Controller CRUD Admin/School Admin
use App\Http\Controllers\Admin\CourseController;
use App\Http\Controllers\Admin\ModuleController;
use App\Http\Controllers\Admin\ModuleContentController;

// Import Middleware
use App\Http\Middleware\CekUserIsActive;


// --- GROUP GUEST (Belum Login) ---
Route::middleware(['guest'])->group(function () {
    Route::get('/', function () { return view('welcome'); });
    Route::get('/login', [AuthController::class, 'login'])->name('login');
    Route::post('/login', [AuthController::class, 'loginPost']);
    Route::get('/register', [AuthController::class, 'register']);
    Route::post('/register', [AuthController::class, 'registerPost'])->name('register');
});

// --- GROUP AUTH (Sudah Login) ---
Route::middleware(['auth'])->group(function() {

    // Logout
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // OTP & Security (Tidak butuh CekUserIsActive dulu, karena mungkin user login untuk verifikasi OTP)
    Route::get('/verify-otp', [OtpController::class, 'index'])->name('otp.verification');
    Route::post('/verify-otp', [OtpController::class, 'verify'])->name('otp.check');
    Route::post('/resend-otp', [OtpController::class, 'resend'])->name('otp.resend');

    Route::get('/change-password', [AuthController::class, 'changePasswordView'])->name('password.change');
    Route::post('/change-password/send', [AuthController::class, 'sendChangePasswordOtp'])->name('password.sendOtp');
    Route::post('/change-password/update', [AuthController::class, 'updatePassword'])->name('password.update');

    Route::middleware([CekUserIsActive::class])->group(function () {

        // --- SUPER ADMIN ---
        Route::middleware(['role:super_admin'])
            ->prefix('superadmin')
            ->name('superadmin.')
            ->group(function () {
               Route::get('/dashboard', [Superadmincontroller::class, 'dashboard'])->name('dashboard');
                Route::resource('sekolah', Superadmin_sekolahcontroller::class);
                Route::resource('adminsekolah', Adminsekolahcontroller::class);
                Route::resource('admin', Superadmincontroller::class);
            });

        // --- STUDENT ---
        Route::middleware(['role:student'])
            ->prefix('user')
            ->name('user.')
            ->group(function () {
                Route::get('/dashboard', function () { return view('user.dashboard'); })->name('dashboard');
                Route::resource('profiles', UserController::class)->only(['show', 'edit', 'update']);

                // Daftar course
                Route::get('/courses', [\App\Http\Controllers\User\PaymentController::class, 'index'])->name('courses.index');
                // Detail course & proses buat Snap Token
                Route::get('/course/{slug}', [\App\Http\Controllers\User\PaymentController::class, 'show'])->name('courses.show');
                // Halaman sukses bayar
                Route::get('/payment/success', [\App\Http\Controllers\User\PaymentController::class, 'success'])->name('payment.success');
                Route::post('/payment/retry/{id}', [App\Http\Controllers\User\PaymentController::class, 'retry'])->name('payment.retry');
                Route::get('/payment/failed', [App\Http\Controllers\User\PaymentController::class, 'failed'])->name('payment.failed');
            });

        // --- ADMIN BIASA ---
        Route::middleware(['role:admin'])
            ->name('admin.')
            ->group(function () {
                Route::get('/dashboard-admin', function () { return view('admin.dashboard'); })->name('dashboard');
            });

        // --- SCHOOL ADMIN ---
        Route::middleware(['role:school_admin'])
            ->name('school_admin.')
            ->group(function () {
                Route::get('/dashboard-sekolah', function () { return 'Halaman admin sekolah'; })->name('dashboard');
            });

        Route::middleware(['role:admin,school_admin'])->group(function () {

            // 1. Course Resource
            Route::resource('courses', CourseController::class);
            // Rute untuk masuk ke halaman belajar (Learning Room)
            Route::get('/user/learning/{slug}', function ($slug) {
            // Nanti di sini logic backend-nya.
            // Sekarang kita return view langsung dengan data dummy title.
            return view('user.courses.learning', ['title' => ucwords(str_replace('-', ' ', $slug))]);
            })->name('user.courses.learning');

            Route::get('/user/learning/{slug}', function ($slug) {
            return view('user.courses.learning', ['title' => ucwords(str_replace('-', ' ', $slug))]);
            })->name('user.courses.learning');

            // 2. Module Routes (Nested dalam Course)
            Route::post('courses/{course}/modules', [ModuleController::class, 'store'])->name('modules.store');
            Route::get('modules/{module}/edit', [ModuleController::class, 'edit'])->name('modules.edit');
            Route::put('modules/{module}', [ModuleController::class, 'update'])->name('modules.update');
            Route::delete('modules/{module}', [ModuleController::class, 'destroy'])->name('modules.destroy');

            // 3. Module Content Routes (Nested dalam Module)
            Route::post('modules/{module}/contents', [ModuleContentController::class, 'store'])->name('contents.store');
            Route::get('contents/{content}/edit', [ModuleContentController::class, 'edit'])->name('contents.edit');
            Route::put('contents/{content}', [ModuleContentController::class, 'update'])->name('contents.update');
            Route::delete('contents/{content}', [ModuleContentController::class, 'destroy'])->name('contents.destroy');
        });

    });


});
