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
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Str;
use App\Http\Controllers\ArticleController;

Route::get('/', function () { return view('welcome'); });
Route::get('/articles', [ArticleController::class, 'index'])->name('articles.index');
Route::get('/articles/{slug}', [ArticleController::class, 'show'])->name('articles.show');

Route::middleware(['guest'])->group(function () {
    Route::get('/login', [AuthController::class, 'login'])->name('login');
    Route::post('/login', [AuthController::class, 'loginPost']);
    Route::get('/register', [AuthController::class, 'register']);
    Route::post('/register', [AuthController::class, 'registerPost'])->name('register');
});

Route::middleware(['auth'])->group(function() {

    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::get('/verify-otp', [OtpController::class, 'index'])->name('otp.verification');
    Route::post('/verify-otp', [OtpController::class, 'verify'])->name('otp.check');
    Route::post('/resend-otp', [OtpController::class, 'resend'])->name('otp.resend');

    Route::get('/change-password', [AuthController::class, 'changePasswordView'])->name('password.change');
    Route::post('/change-password/send', [AuthController::class, 'sendChangePasswordOtp'])->name('password.sendOtp');
    Route::post('/change-password/update', [AuthController::class, 'updatePassword'])->name('password.update');

    Route::get('/notifications/unread-count', function () {
        return DB::table('notifications')
            ->where('notifiable_id', auth()->id())
            ->whereNull('read_at')
            ->count();
    });

    Route::post('/notifications/read-all', function () {
        DB::table('notifications')
            ->where('notifiable_id', auth()->id())
            ->whereNull('read_at')
            ->update(['read_at' => now()]);
        return back();
    });

    Route::middleware([CekUserIsActive::class])->group(function () {

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
                Route::get('/dashboard', function () {
                    return view('user.dashboard');
                })->name('dashboard');
                
                Route::get('/profile', function () {
                    $user = Auth::user();
                    return view('user.profile.show', compact('user'));
                })->name('profile.show');
                
                Route::get('/profile/edit', function () {
                    $user = Auth::user();
                    return view('user.profile.edit', compact('user'));
                })->name('profile.edit');
                
                Route::put('/profile/update', function (Request $request) {
                    $user = Auth::user();
                    
                    $request->validate([
                        'nama_lengkap' => 'nullable|string|max:255',
                        'sekolah' => 'nullable|string|max:255',
                        'alamat' => 'nullable|string|max:500',
                        'no_hp' => 'nullable|string|max:20',
                    ]);
                    
                    $user->update($request->only([
                        'nama_lengkap',
                        'sekolah',
                        'alamat',
                        'no_hp'
                    ]));
                    
                    return redirect()->route('user.profile.show');
                })->name('profile.update');

                Route::get('/courses', [\App\Http\Controllers\User\UserCourseController::class, 'index'])->name('courses.index');
                Route::get('/course/{slug}', [UserCourseController::class, 'show'])->name('courses.show');
                Route::get('/payment/success', [\App\Http\Controllers\User\PaymentController::class, 'success'])->name('payment.success');
                Route::post('/payment/retry/{id}', [App\Http\Controllers\User\PaymentController::class, 'retry'])->name('payment.retry');
                Route::get('/payment/failed', [App\Http\Controllers\User\PaymentController::class, 'failed'])->name('payment.failed');
                Route::post('/payment/process', [App\Http\Controllers\User\PaymentController::class, 'processPayment'])->name('payment.process');
                Route::get('/course/{slug}/learning', [UserCourseController::class, 'learning'])->name('courses.learning');

                Route::post('/content/{content}/complete', function ($contentId) {
                    $userId = Auth::id();

                    $enrollment = DB::table('course_enrollments')
                        ->where('user_id', $userId)
                        ->whereIn('course_id', function ($q) use ($contentId) {
                            $q->select('modules.course_id')
                                ->from('module_contents')
                                ->join('modules', 'modules.id', '=', 'module_contents.module_id')
                                ->where('module_contents.id', $contentId);
                        })
                        ->first();

                    if (!$enrollment) abort(403);

                    DB::table('content_progress')->updateOrInsert(
                        [
                            'enrollment_id' => $enrollment->id,
                            'content_id' => $contentId,
                        ],
                        [
                            'is_completed' => 1,
                            'completed_at' => now(),
                        ]
                    );

                    $totalContent = DB::table('module_contents')
                        ->join('modules', 'modules.id', '=', 'module_contents.module_id')
                        ->where('modules.course_id', $enrollment->course_id)
                        ->count();

                    $completedContent = DB::table('content_progress')
                        ->where('enrollment_id', $enrollment->id)
                        ->where('is_completed', 1)
                        ->count();

                    $progress = round(($completedContent / $totalContent) * 100, 2);

                    DB::table('course_enrollments')
                        ->where('id', $enrollment->id)
                        ->update([
                            'progress_percentage' => $progress
                        ]);

                    if ($totalContent > 0 && $totalContent === $completedContent) {
                        DB::table('course_enrollments')
                            ->where('id', $enrollment->id)
                            ->update([
                                'status' => 'completed',
                                'completed_at' => now()
                            ]);

                        $course = DB::table('courses')->where('id', $enrollment->course_id)->first();

                        $alreadyNotified = DB::table('notifications')
                            ->where('notifiable_id', $userId)
                            ->where('type', 'course_completed')
                            ->where('data', 'like', '%"course_id":'.$course->id.'%')
                            ->exists();

                        if (!$alreadyNotified) {
                            DB::table('notifications')->insert([
                                'id' => Str::uuid(),
                                'type' => 'course_completed',
                                'notifiable_type' => 'App\Models\User',
                                'notifiable_id' => $userId,
                                'data' => json_encode([
                                    'title' => 'Course Selesai',
                                    'message' => 'Kamu telah menyelesaikan course: '.$course->title,
                                    'course_id' => $course->id
                                ]),
                                'created_at' => now(),
                                'updated_at' => now()
                            ]);
                        }
                    }

                    return back();
                });

                Route::get('/certificate/{course}', function ($courseId) {
                    $enrollment = DB::table('course_enrollments')
                        ->where('user_id', auth()->id())
                        ->where('course_id', $courseId)
                        ->where('status', 'completed')
                        ->first();

                    if (!$enrollment) abort(403);

                    $user = DB::table('users')->where('id', auth()->id())->first();
                    $course = DB::table('courses')->where('id', $courseId)->first();

                    DB::table('notifications')
                        ->where('notifiable_id', auth()->id())
                        ->where('data', 'like', '%"course_id":'.$courseId.'%')
                        ->update(['read_at' => now()]);

                    $pdf = Pdf::loadView('user.certificate.pdf', compact('user', 'course'));
                    return $pdf->download('sertifikat-'.$course->slug.'.pdf');
                });
            });

        Route::middleware(['role:user'])->group(function () {
            Route::get('/spin-wheel', [SpinGameController::class, 'index'])->name('user.spin');
            Route::post('/spin-wheel-process', [SpinGameController::class, 'spinProcess'])->name('user.spin.process');
        });

        Route::middleware(['role:admin'])
            ->name('admin.')
            ->group(function () {
                Route::get('/dashboard-admin', function () { return view('admin.dashboard'); })->name('dashboard');
            });

        Route::middleware(['role:school_admin'])
            ->name('school_admin.')
            ->group(function () {
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