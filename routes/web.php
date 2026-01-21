<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\OtpController;
use App\Http\Controllers\superadmin\Superadmincontroller;
use App\Http\Controllers\superadmin\Adminsekolahcontroller;
use App\Http\Controllers\superadmin\Superadmin_sekolahcontroller;
use App\Http\Controllers\User\PaymentController;
use App\Http\Controllers\User\SpinGameController;
use App\Http\Controllers\User\UserCourseController;
use App\Http\Controllers\Admin\CourseController;
use App\Http\Controllers\Admin\ModuleController;
use App\Http\Controllers\Admin\ModuleContentController;
use App\Http\Middleware\CekUserIsActive;

// --- GROUP GUEST (Belum Login) ---
Route::middleware(['guest'])->group(function () {
    Route::get('/', function () { return view('welcome'); });
    Route::get('/login', [AuthController::class, 'login'])->name('login');
    Route::post('/login', [AuthController::class, 'loginPost']);
    Route::get('/register', [AuthController::class, 'register']);
    Route::post('/register', [AuthController::class, 'registerPost'])->name('register');
    
    // --- FITUR LUPA PASSWORD (RESET FLOW) ---
    // 1. Tampilkan Halaman Input Email
    Route::get('/forgot-password', [AuthController::class, 'showForgotPasswordForm'])->name('password.forgot');
    
    // 2. Proses Kirim OTP ke Email
    Route::post('/forgot-password/send', [AuthController::class, 'sendForgotPasswordOtp'])->name('password.sendOtp'); // Route name disamakan dengan form login
    
    // 3. Tampilkan Halaman Input OTP
    Route::get('/forgot-password/otp', [AuthController::class, 'showForgotOtpView'])->name('password.forgot.otp');
    
    // 4. Proses Verifikasi OTP
    Route::post('/forgot-password/verify', [AuthController::class, 'verifyForgotOtp'])->name('password.forgot.verify');
    
    // 5. Tampilkan Halaman Input Password Baru
    Route::get('/reset-password', [AuthController::class, 'showResetPasswordForm'])->name('password.reset.form');
    
    // 6. Proses Update Password Baru
    Route::post('/reset-password', [AuthController::class, 'updateResetPassword'])->name('password.reset.update');
});

// --- GROUP AUTH (Sudah Login) ---
Route::middleware(['auth'])->group(function() {

    // Logout
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // OTP Verification (Aktivasi Akun)
    Route::get('/verify-otp', [OtpController::class, 'index'])->name('otp.verification');
    Route::post('/verify-otp', [OtpController::class, 'verify'])->name('otp.check');
    Route::post('/resend-otp', [OtpController::class, 'resend'])->name('otp.resend');

    // --- GANTI PASSWORD (USER LOGIN) ---
    Route::get('/change-password', [AuthController::class, 'changePasswordView'])->name('password.change');
    Route::post('/change-password/request', [AuthController::class, 'requestChangePassword'])->name('password.request');
    Route::get('/change-password/otp', [AuthController::class, 'showChangePasswordOtpView'])->name('password.change.otp');
    Route::post('/change-password/verify', [AuthController::class, 'verifyAndChangePassword'])->name('password.verify');
    Route::post('/change-password/cancel', [AuthController::class, 'cancelChangePassword'])->name('password.cancel');

    // Middleware Cek User Active
    Route::middleware([CekUserIsActive::class])->group(function () {

        // ... (Sisa route admin/student/sekolah biarkan sama) ...
        Route::middleware(['role:super_admin'])
            ->prefix('superadmin')
            ->name('superadmin.')
            ->group(function () {
               Route::get('/dashboard', [Superadmincontroller::class, 'dashboard'])->name('dashboard');
                Route::resource('sekolah', Superadmin_sekolahcontroller::class);
                Route::resource('adminsekolah', Adminsekolahcontroller::class);
                Route::resource('admin', Superadmincontroller::class);
            });

        Route::middleware(['role:student'])
            ->prefix('user') 
            ->name('user.')  
            ->group(function () {
                Route::get('/dashboard', function () { return view('user.dashboard'); })->name('dashboard');
                Route::resource('profiles', UserController::class)->only(['show', 'edit', 'update']);
                Route::get('/courses', [\App\Http\Controllers\User\UserCourseController::class, 'index'])->name('courses.index');
                Route::get('course/{slug}', [UserCourseController::class, 'show'])->name('courses.show');
                Route::get('course/{slug}/learning/{contentId?}', [UserCourseController::class, 'learning'])->name('courses.learning');
                Route::get('/payment/success', [\App\Http\Controllers\User\PaymentController::class, 'success'])->name('payment.success');
                Route::post('/payment/retry/{id}', [App\Http\Controllers\User\PaymentController::class, 'retry'])->name('payment.retry');
                Route::get('/payment/failed', [App\Http\Controllers\User\PaymentController::class, 'failed'])->name('payment.failed');
                Route::post('/payment/process', [App\Http\Controllers\User\PaymentController::class, 'processPayment'])->name('payment.process');
                Route::post('/payment/check-voucher', [App\Http\Controllers\User\PaymentController::class, 'checkVoucher'])->name('payment.check_voucher'); 
            });

        Route::middleware(['role:user'])->group(function () {
            Route::get('/spin-wheel', [SpinGameController::class, 'index'])->name('user.spin');
            Route::post('/spin-wheel-process', [SpinGameController::class, 'spinProcess'])->name('user.spin.process');
        });

        Route::middleware(['role:admin'])->name('admin.')->group(function () {
            Route::get('/dashboard-admin', function () { return view('admin.dashboard'); })->name('dashboard');
        });

        Route::middleware(['role:school_admin'])->name('school_admin.')->group(function () {
            Route::get('/dashboard-sekolah', function () { return 'Halaman admin sekolah'; })->name('dashboard');
        });

        Route::middleware(['role:admin,school_admin'])->group(function () {
            Route::resource('courses', CourseController::class);
            Route::post('courses/{course}/modules', [ModuleController::class, 'store'])->name('modules.store');
            Route::get('modules/{module}/edit', [ModuleController::class, 'edit'])->name('modules.edit');
            Route::put('modules/{module}', [ModuleController::class, 'update'])->name('modules.update');
            Route::delete('modules/{module}', [ModuleController::class, 'destroy'])->name('modules.destroy');
            Route::post('modules/{module}/contents', [ModuleContentController::class, 'store'])->name('contents.store');
            Route::get('contents/{content}/edit', [ModuleContentController::class, 'edit'])->name('contents.edit');
            Route::put('contents/{content}', [ModuleContentController::class, 'update'])->name('contents.update');
            Route::delete('contents/{content}', [ModuleContentController::class, 'destroy'])->name('contents.destroy');
        });
    }); 
});