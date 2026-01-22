<?php

namespace App\Http\Controllers\user;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Course;
use App\Models\Module;
use App\Models\ModuleContent;
class UserCourseController extends Controller
{
   public function index()
{
    $user = Auth::user();
    $userId = $user->id;

    // Mulai query dengan join ke tabel courses
    $query = DB::table('course_enrollments')
        ->join('courses', 'courses.id', '=', 'course_enrollments.course_id')
        ->where('course_enrollments.user_id', $userId);

    // 1. LOGIKA FILTER SCHOOL_ID (Hanya tampilkan kursus yang diizinkan)
    if (!$user->school_id) {
        // Jika user tidak punya sekolah, hanya ambil kursus umum (school_id NULL)
        $query->whereNull('courses.school_id');
    } else {
        // Jika user punya sekolah, ambil yang umum (NULL) ATAU yang sesuai sekolahnya
        $query->where(function($q) use ($user) {
            $q->whereNull('courses.school_id')
              ->orWhere('courses.school_id', $user->school_id);
        });
    }

    // 2. Pilih kolom yang ingin ditampilkan
    $courses = $query->select(
            'courses.*',
            'course_enrollments.progress_percentage',
            'course_enrollments.status'
        )
        ->latest('course_enrollments.created_at')
        ->get();

    return view('user.courses.index', compact('courses'));
}

    public function show($slug)
    {
        $userId = Auth::id();

        // 1. Ambil data course dengan module dan contents-nya
        $course = Course::where('slug', $slug)
            ->with(['modules.contents'])
            ->firstOrFail();

        // 2. Hitung Total Konten (Solusi Error: Undefined variable $totalContent)
        // Kita menjumlahkan semua content yang ada di dalam setiap module
        $totalContent = $course->modules->sum(function ($module) {
            return $module->contents->count();
        });

        // 3. Cek apakah user sudah terdaftar/beli
        $isEnrolled = $course->enrollments()->where('user_id', $userId)->exists();

        // 4. Kirim semua variabel ke View
        return view('user.courses.show', compact('course', 'isEnrolled', 'totalContent'));
    }

   public function learning($slug, $contentId = null)
{
    $course = Course::with(['modules' => function($q) {
        $q->orderBy('id', 'asc');
    }, 'modules.contents' => function($q) {
        $q->orderBy('id', 'asc');
    }])
    ->where(function($query) use ($slug) {
        $query->where('slug', $slug)->orWhere('id', $slug);
    })
    ->firstOrFail();

    $userId = Auth::id();
    $enrollment = DB::table('course_enrollments')
                    ->where('user_id', $userId)
                    ->where('course_id', $course->id)
                    ->first();

    // Auto-Enroll jika belum ada
    if (!$enrollment) {
        $enrollmentId = DB::table('course_enrollments')->insertGetId([
            'user_id' => $userId,
            'course_id' => $course->id,
            'status' => 'active',
            'last_content_id' => null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        $enrollment = DB::table('course_enrollments')->where('id', $enrollmentId)->first();
    }

    $activeContent = null;
    if ($contentId) {
        // Prioritas 1: Sesuai ID yang diklik di sidebar/tombol next
        $activeContent = ModuleContent::find($contentId);
    } else {
        // Prioritas 2: Resume dari database jika baru masuk
        if ($enrollment->last_content_id) {
            $activeContent = ModuleContent::find($enrollment->last_content_id);
        }
        // Prioritas 3: Materi pertama jika semuanya kosong
        if (!$activeContent) {
            $firstModule = $course->modules->first();
            $activeContent = $firstModule ? $firstModule->contents->first() : null;
        }
    }

    if (!$activeContent) {
        return redirect()->route('user.courses.show', $course->slug)->with('error', 'Materi tidak ditemukan.');
    }

    // Update progress terakhir di DB
    DB::table('course_enrollments')
        ->where('id', $enrollment->id)
        ->update(['last_content_id' => $activeContent->id, 'updated_at' => now()]);

    // Logika Auto-Complete (Cek materi terakhir)
    $lastModule = $course->modules->last();
    if ($lastModule) {
        $lastContentOfCourse = $lastModule->contents->last();
        if ($lastContentOfCourse && $activeContent->id == $lastContentOfCourse->id) {
            DB::table('course_enrollments')->where('id', $enrollment->id)->update(['status' => 'completed']);
            $enrollment->status = 'completed';
        }
    }

    $isCompleted = ($enrollment->status == 'completed' || $enrollment->status == 1);

    return view('user.courses.learning', [
        'course' => $course,
        'activeContent' => $activeContent,
        'isCompleted' => $isCompleted
    ]);
}

   public function catalog(Request $request)
{
    $user = Auth::user();
    $query = Course::query();

    // 1. LOGIKA FILTER SCHOOL_ID (KEAMANAN DATA)
    if (!$user->school_id) {
        // Jika user tidak punya sekolah, hanya tampilkan kursus yang school_id-nya NULL
        $query->whereNull('school_id');
    } else {
        // Jika user punya sekolah, tampilkan yang NULL (umum) ATAU yang sesuai school_id user
        $query->where(function($q) use ($user) {
            $q->whereNull('school_id')
              ->orWhere('school_id', $user->school_id);
        });
    }

    // 2. Logika Search (Berdasarkan nama/title course)
    if ($request->filled('search')) {
        $query->where('title', 'like', '%' . $request->search . '%');
    }

    // 3. Logika Filter Level
    if ($request->filled('level') && $request->level != 'Semua') {
        $query->where('level', $request->level);
    }

    // Ambil data dengan pagination
    $courses = $query->latest()->paginate(9)->withQueryString();

    return view('user.courses.catalog', compact('courses'));
}
}