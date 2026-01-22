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
        $userId = Auth::id();

        $courses = DB::table('course_enrollments')
            ->join('courses', 'courses.id', '=', 'course_enrollments.course_id')
            ->where('course_enrollments.user_id', $userId)
            ->select(
                'courses.*',
                'course_enrollments.progress_percentage',
                'course_enrollments.status'
            )
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
    // 1. Ambil Data Course
    $course = Course::with(['modules' => function($q) {
        $q->orderBy('id', 'asc');
    }, 'modules.contents' => function($q) {
        $q->orderBy('id', 'asc');
    }])
    ->where(function($query) use ($slug) {
        $query->where('slug', $slug)
              ->orWhere('id', $slug);
    })
    ->firstOrFail();

    $userId = Auth::id();

    // 2. Cek/Buat Enrollment
    $enrollment = DB::table('course_enrollments')
                    ->where('user_id', $userId)
                    ->where('course_id', $course->id)
                    ->first();

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

    // 3. Logika Konten Aktif
    $activeContent = null;

    if ($contentId) {
        $activeContent = ModuleContent::find($contentId);
        
        // Simpan progress jika materi ditemukan
        if ($activeContent) {
            DB::table('course_enrollments')
                ->where('id', $enrollment->id)
                ->update(['last_content_id' => $contentId, 'updated_at' => now()]);
        }
    } else {
        // Resume dari history
        if ($enrollment->last_content_id) {
            $activeContent = ModuleContent::find($enrollment->last_content_id);
        }

        // Jika history null/tidak valid, ambil materi pertama
        if (!$activeContent) {
            if ($course->modules->isNotEmpty() && $course->modules->first()->contents->isNotEmpty()) {
                $activeContent = $course->modules->first()->contents->first();
                
                // Simpan start point
                DB::table('course_enrollments')
                    ->where('id', $enrollment->id)
                    ->update(['last_content_id' => $activeContent->id]);
            }
        }
    }

    // [FIX PENTING] Jika Kursus Kosong (Tidak ada materi sama sekali)
    // Kita buat object dummy atau redirect agar tidak error di View
    if (!$activeContent) {
        return redirect()->route('user.courses.show', $course->slug)
                         ->with('error', 'Kursus ini belum memiliki materi.');
    }

    // 4. Cek Status Penyelesaian
    // [UPDATE] Logika Auto-Complete (Materi Terakhir)
    $lastModule = $course->modules->last();
    if ($lastModule) {
        $lastContent = $lastModule->contents->last();
        // Jika materi yang dibuka adalah materi terakhir -> Set Completed
        if ($lastContent && $activeContent->id == $lastContent->id) {
            DB::table('course_enrollments')
                ->where('id', $enrollment->id)
                ->update(['status' => 'completed']);
            $enrollment->status = 'completed'; // Update variable lokal
        }
    }

    $isCompleted = false;
    if (isset($enrollment->status) && ($enrollment->status == 'completed' || $enrollment->status == 1)) {
        $isCompleted = true;
    }

    return view('user.courses.learning', [
        'title' => $course->name,
        'course' => $course,
        'activeContent' => $activeContent, // Pastikan ini tidak null (sudah dicek di atas)
        'isCompleted' => $isCompleted
    ]);
}

    public function catalog(Request $request)
{
    // Mulai query dari model Course
    $query = Course::query();

    // 1. Logika Search (Berdasarkan nama course)
    if ($request->has('search') && $request->search != '') {
        $query->where('title', 'like', '%' . $request->search . '%');
    }

    // 2. Logika Filter Level (Berdasarkan parameter 'level' di URL)
    if ($request->has('level') && $request->level != 'Semua') {
        $query->where('level', $request->level);
    }

    // Ambil data (menggunakan pagination agar rapi jika data banyak)
    $courses = $query->latest()->paginate(9)->withQueryString();

    return view('user.courses.catalog', compact('courses'));
}
}