<?php

namespace App\Http\Controllers\user;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Course;
use App\Models\ModuleContent;

class UserCourseController extends Controller
{
    /**
     * Menampilkan daftar kursus milik user (Terbeli atau Terdaftar)
     */
    public function index()
    {
        $user = Auth::user();
        $userId = $user->id;

        // Ambil kursus yang sudah dibayar (status settlement di tabel transactions)
        // ATAU kursus yang user sudah terdaftar di course_enrollments
        $courses = Course::whereHas('orders', function($q) use ($userId) {
                $q->where('user_id', $userId)->where('payment_status', 'settlement');
            })
            ->orWhereHas('enrollments', function($q) use ($userId) {
                $q->where('user_id', $userId);
            })
            ->when($user->school_id, function($query) use ($user) {
                // Jika user punya sekolah, filter juga berdasarkan akses sekolah
                return $query->where(function($q) use ($user) {
                    $q->whereNull('school_id')->orWhere('school_id', $user->school_id);
                });
            }, function($query) {
                // Jika tidak punya sekolah, hanya ambil yang umum
                return $query->whereNull('school_id');
            })
            ->get();

        return view('user.courses.index', compact('courses'));
    }

    /**
     * Detail Kursus
     */
    public function show($slug)
    {
        $userId = Auth::id();
        $course = Course::where('slug', $slug)->with(['modules.contents'])->firstOrFail();

        // Hitung total materi
        $totalContent = $course->modules->sum(function ($module) {
            return $module->contents->count();
        });

        // Cek Pembayaran di tabel TRANSACTIONS (Bukan orders)
        $isPaid = DB::table('transactions')
                    ->where('user_id', $userId)
                    ->where('course_id', $course->id)
                    ->where('payment_status', 'settlement')
                    ->exists();

        // Cek Pendaftaran Progres
        $isEnrolled = DB::table('course_enrollments')
                        ->where('user_id', $userId)
                        ->where('course_id', $course->id)
                        ->exists();

        // User punya akses jika sudah bayar ATAU sudah terdaftar (gratis)
        $hasAccess = $isPaid || $isEnrolled;

        return view('user.courses.show', [
            'course' => $course,
            'isEnrolled' => $hasAccess, 
            'totalContent' => $totalContent
        ]);
    }

    /**
     * Ruang Belajar (Learning Room)
     */
   public function learning($slug, $contentId = null)
{
    // 1. Cari Course
    $course = Course::with(['modules.contents'])->where('slug', $slug)->orWhere('id', $slug)->firstOrFail();
    $userId = Auth::id();

    // 2. VALIDASI PEMBAYARAN (Gunakan tabel 'transactions' sesuai database Anda)
    $isPaid = DB::table('transactions')
                ->where('user_id', $userId)
                ->where('course_id', $course->id)
                ->where('payment_status', 'settlement')
                ->exists();

    // 3. JIKA BELUM BAYAR (Hanya untuk kursus berbayar)
    if ($course->price > 0 && !$isPaid) {
        return redirect()->route('user.courses.show', $course->slug)
                         ->with('error', 'Akses ditolak: Status pembayaran belum lunas (Settlement).');
    }

    // 4. LOGIKA ENROLLMENT (Pastikan data ada di tabel course_enrollments)
    $enrollment = DB::table('course_enrollments')
                    ->where('user_id', $userId)
                    ->where('course_id', $course->id)
                    ->first();

    if (!$enrollment) {
        $enrollmentId = DB::table('course_enrollments')->insertGetId([
            'user_id' => $userId,
            'course_id' => $course->id,
            'status' => 'active',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        $enrollment = DB::table('course_enrollments')->where('id', $enrollmentId)->first();
    }

    // 5. TENTUKAN MATERI YANG DITAMPILKAN
    $activeContent = null;
    if ($contentId) {
        $activeContent = \App\Models\ModuleContent::find($contentId);
    } else {
        // Coba resume dari materi terakhir yang dibuka
        if (!empty($enrollment->last_content_id)) {
            $activeContent = \App\Models\ModuleContent::find($enrollment->last_content_id);
        }
        
        // Jika belum pernah buka sama sekali, ambil materi PERTAMA
        if (!$activeContent) {
            $firstModule = $course->modules->sortBy('id')->first();
            if ($firstModule) {
                $activeContent = $firstModule->contents->sortBy('id')->first();
            }
        }
    }

    // CEK: Jika kursus ternyata kosong (belum ada materi)
    if (!$activeContent) {
        return redirect()->route('user.courses.show', $course->slug)
                         ->with('error', 'Kursus ini belum memiliki materi untuk dipelajari.');
    }

    // 6. SIMPAN PROGRESS TERAKHIR
    DB::table('course_enrollments')
        ->where('id', $enrollment->id)
        ->update([
            'last_content_id' => $activeContent->id,
            'updated_at' => now()
        ]);

    return view('user.courses.learning', [
        'course' => $course,
        'activeContent' => $activeContent,
        'isCompleted' => ($enrollment->status === 'completed')
    ]);
}

    /**
     * Katalog Kursus
     */
    public function catalog(Request $request)
{
    $user = Auth::user();
    $query = Course::query();

    // 1. FILTER SCHOOL_ID
    // User tanpa school_id hanya bisa melihat kursus umum (school_id NULL)
    // User dengan school_id bisa melihat kursus umum DAN kursus sekolahnya
    $query->where(function($q) use ($user) {
        $q->whereNull('school_id');
        if ($user->school_id) {
            $q->orWhere('school_id', $user->school_id);
        }
    });

    // 2. LOGIKA SEARCH
    if ($request->filled('search')) {
        $query->where('title', 'like', '%' . $request->search . '%');
    }

    // 3. LOGIKA FILTER LEVEL
    if ($request->filled('level') && $request->level != 'Semua') {
        $query->where('level', $request->level);
    }

    $courses = $query->latest()->paginate(9)->withQueryString();
    
    return view('user.courses.catalog', compact('courses'));
}
}