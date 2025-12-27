<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\superadmin\Superadmincontroller;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\RedirectIfAuthenticated;
use Illuminate\Support\Facades\Auth;
use App\Http\Middleware\CheckRole;

Route::middleware(['guest'])->group(function () {
    Route::get('/login', [AuthController::class, 'login'])->name('login');
    Route::post('/login', [AuthController::class, 'loginPost']);
    Route::get('/register', [AuthController::class, 'register']);
    Route::post('/register', [AuthController::class, 'registerPost']);
});

// --- LOGOUT ---
Route::post('/logout', [AuthController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');

// --- SUPER ADMIN ---
Route::middleware(['auth', 'role:super_admin'])
    ->prefix('superadmin')
    ->name('superadmin.')
    ->group(function () {

        Route::get('/dashboard', function () {
            return view('superadmin.dashboard');
        })->name('dashboard');

        Route::resource('admins', Superadmincontroller::class);
    });


Route::middleware(['auth', 'role:student'])
    ->prefix('student')
    ->name('student.')
    ->group(function () {

        // Hasil nama route: 'student.dashboard'
        Route::get('/dashboard', function () {
            return view('student.dashboard');
        })->name('dashboard');

        Route::resource('profiles', UserController::class)->only(['show', 'edit', 'update']);
    });


Route::middleware(['auth', 'role:admin'])
    ->name('admin.')
    ->group(function () {


        Route::get('/dashboard-admin', function () {
            return 'Halaman admin biasa';
        })->name('dashboard');
    });


Route::middleware(['auth', 'role:school_admin'])
    ->name('school_admin')
    ->group(function () {


        Route::get('/dashboard-sekolah', function () {
            return 'Halaman admin sekolah';
        })->name('dashboard');
    });

