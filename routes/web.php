<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\user\UserController;
use App\Http\Controllers\OtpController;
use App\Http\Controllers\superadmin\Superadmincontroller;
use App\Http\Controllers\superadmin\Adminsekolahcontroller;
use App\Http\Controllers\superadmin\Superadmin_sekolahcontroller;
use App\Http\Controllers\User\PaymentController;
use App\Http\Controllers\User\SpinGameController;
use App\Http\Controllers\User\UserCourseController;
use App\Http\Controllers\Adminsekolah\SchoolTokenController;
// --- IMPORT ADMIN GENERAL (JANGAN DIHAPUS) ---
use App\Http\Controllers\Admin\CourseController;
use App\Http\Controllers\Admin\ModuleController;
use App\Http\Controllers\Admin\ModuleContentController;
use App\Http\Controllers\ArticleController;

// --- IMPORT ADMIN SEKOLAH ---
use App\Http\Controllers\AdminSekolah\AdminSekolahCourseController;
use App\Http\Controllers\AdminSekolah\AdminSekolahModuleController;
use App\Http\Controllers\AdminSekolah\AdminSekolahModuleContentController;

use App\Http\Controllers\user\AllCourseController;
use App\Http\Middleware\CekUserIsActive;
use Illuminate\Support\Facades\DB;

// --- GUEST ROUTES ---
Route::middleware(['guest'])->group(function () {
  Route::get('/', function () {
    return view('welcome');
  });
  Route::get('/login', [AuthController::class, 'login'])->name('login');
  Route::post('/login', [AuthController::class, 'loginPost']);
  Route::get('/register', [AuthController::class, 'register']);
  Route::post('/register', [AuthController::class, 'registerPost'])->name('register');

  // Fitur Reset Password
  Route::get('/forgot-password', [AuthController::class, 'showForgotPasswordForm'])->name('password.forgot');
  Route::post('/forgot-password/send', [AuthController::class, 'sendForgotPasswordOtp'])->name('password.sendOtp');
  Route::get('/forgot-password/otp', [AuthController::class, 'showForgotOtpView'])->name('password.forgot.otp');
  Route::post('/forgot-password/verify', [AuthController::class, 'verifyForgotOtp'])->name('password.forgot.verify');
  Route::get('/reset-password', [AuthController::class, 'showResetPasswordForm'])->name('password.reset.form');
  Route::post('/reset-password', [AuthController::class, 'updateResetPassword'])->name('password.reset.update');

  Route::get('/articles', [ArticleController::class, 'index'])->name('articles.index');
  Route::get('/articles/{slug}', [ArticleController::class, 'show'])->name('articles.show');
});

// --- AUTHENTICATED ROUTES ---
Route::middleware(['auth'])->group(function () {

  Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

  // OTP & Security
  Route::get('/verify-otp', [OtpController::class, 'index'])->name('otp.verification');
  Route::post('/verify-otp', [OtpController::class, 'verify'])->name('otp.check');
  Route::post('/resend-otp', [OtpController::class, 'resend'])->name('otp.resend');

  // Ganti Password
  Route::get('/change-password', [AuthController::class, 'changePasswordView'])->name('password.change');
  Route::post('/change-password/request', [AuthController::class, 'requestChangePassword'])->name('password.request');
  Route::get('/change-password/otp', [AuthController::class, 'showChangePasswordOtpView'])->name('password.change.otp');
  Route::post('/change-password/verify', [AuthController::class, 'verifyAndChangePassword'])->name('password.verify');
  Route::post('/change-password/cancel', [AuthController::class, 'cancelChangePassword'])->name('password.cancel');

  // Notifications
  Route::get('/notifications/unread-count', function () {
    return DB::table('notifications')->where('notifiable_id', auth()->id())->whereNull('read_at')->count();
  });
  Route::post('/notifications/read-all', function () {
    DB::table('notifications')->where('notifiable_id', auth()->id())->whereNull('read_at')->update(['read_at' => now()]);
    return back();
  });

  Route::middleware([CekUserIsActive::class])->group(function () {

    // 1. SUPER ADMIN
    Route::middleware(['role:super_admin'])->prefix('superadmin')->name('superadmin.')->group(function () {
      Route::get('/dashboard', [Superadmincontroller::class, 'dashboard'])->name('dashboard');
      Route::resource('sekolah', Superadmin_sekolahcontroller::class);
      Route::resource('adminsekolah', Adminsekolahcontroller::class);
      Route::resource('admin', Superadmincontroller::class);
    });

    // 2. STUDENT / USER
    Route::middleware(['role:student'])->prefix('user')->name('user.')->group(function () {

      Route::resource('profiles', UserController::class)->only(['show', 'edit', 'update']);

      Route::get('/dashboard', function () {
        return view('user.dashboard');
      })->name('dashboard');
      Route::get('/courses', [UserCourseController::class, 'index'])->name('courses.index');
      Route::get('/course/{slug}', [UserCourseController::class, 'show'])->name('courses.show');
      Route::get('/course/{slug}/learning/{contentId?}', [UserCourseController::class, 'learning'])->name('courses.learning');
      Route::get('/catalog', [UserCourseController::class, 'catalog'])->name('courses.catalog');

      Route::get('/spin-wheel', [SpinGameController::class, 'index'])->name('spin');
      Route::post('/spin-wheel-process', [SpinGameController::class, 'spinProcess'])->name('spin.process');
      Route::get('/spin-history', [SpinGameController::class, 'history'])->name('spin.history');

      Route::post('/join-school', [UserController::class, 'joinSchool'])->name('join_school');

      // Payment
      Route::get('/payment/success', [PaymentController::class, 'success'])->name('payment.success');
      Route::get('/payment/failed', [PaymentController::class, 'failed'])->name('payment.failed');
      Route::post('/payment/retry/{id}', [PaymentController::class, 'retry'])->name('payment.retry');
      Route::post('/payment/process', [PaymentController::class, 'processPayment'])->name('payment.process');
      Route::post('/payment/check-voucher', [PaymentController::class, 'checkVoucher'])->name('payment.check_voucher');
    });

    // 3. ADMIN BIASA (GENERAL)
    Route::middleware(['role:admin'])->prefix('admin')->name('admin.')->group(function () {
      // Gunakan Controller Admin General
      Route::get('/dashboard', [CourseController::class, 'dashboard'])->name('dashboard');
    });

    // 4. SCHOOL ADMIN
    Route::middleware(['role:school_admin'])->prefix('schooladmin')->name('school_admin.')->group(function () {
      // Gunakan Controller Admin Sekolah
      Route::get('/dashboard', [AdminSekolahCourseController::class, 'dashboard'])->name('dashboard');
      Route::post('/generate-token', [SchoolTokenController::class, 'generate'])->name('generate_token');
    });

    // 5. SHARED CRUD (Dikelola oleh AdminSekolah Controllers karena sudah mendukung Multitenancy)
    Route::middleware(['role:admin,school_admin'])->group(function () {
      // Kita gunakan AdminSekolahControllers karena di dalamnya sudah kita beri logika:
      // Jika role = admin, school_id = null. Jika role = school_admin, school_id = user->school_id.
      Route::resource('courses', AdminSekolahCourseController::class);

      // Module Routes
      Route::post('courses/{course}/modules', [AdminSekolahModuleController::class, 'store'])->name('modules.store');
      Route::get('modules/{module}/edit', [AdminSekolahModuleController::class, 'edit'])->name('modules.edit');
      Route::put('modules/{module}', [AdminSekolahModuleController::class, 'update'])->name('modules.update');
      Route::delete('modules/{module}', [AdminSekolahModuleController::class, 'destroy'])->name('modules.destroy');

      // Module Content Routes
      Route::post('modules/{module}/contents', [AdminSekolahModuleContentController::class, 'store'])->name('contents.store');
      Route::get('contents/{content}/edit', [AdminSekolahModuleContentController::class, 'edit'])->name('contents.edit');
      Route::put('contents/{content}', [AdminSekolahModuleContentController::class, 'update'])->name('contents.update');
      Route::delete('contents/{content}', [AdminSekolahModuleContentController::class, 'destroy'])->name('contents.destroy');
    });
  }); // End CekUserIsActive
}); // End Auth Group