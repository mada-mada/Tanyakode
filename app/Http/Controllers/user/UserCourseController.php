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
        $courses = Course::all();
        return view('user.courses.index', compact('courses'));
    }

    public function show($slug)
    {
        // Panggil relasi 'modules.contents' sesuai nama fungsi di Model
        $course = Course::with(['modules.contents'])->find($slug);

        if (!$course) {
            $course = Course::with(['modules.contents'])->where('slug', $slug)->firstOrFail();
        }

        return view('user.courses.show', compact('course'));
    }

  public function learning($slug, $contentId = null)
{
    // 1. Ambil Data Course & Modules (Sama seperti sebelumnya)
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

    // 2. Ambil Data Enrollment (Sama seperti sebelumnya)
    $enrollment = DB::table('course_enrollments')
                    ->where('user_id', $userId)
                    ->where('course_id', $course->id)
                    ->first();

    // Auto-Enroll jika belum ada (Sama seperti sebelumnya)
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

    // 3. Logika Menentukan Konten Aktif
    $activeContent = null;

    if ($contentId) {
        $activeContent = ModuleContent::find($contentId);
    } else {
        if ($enrollment->last_content_id) {
            $activeContent = ModuleContent::find($enrollment->last_content_id);
        }
        if (!$activeContent && $course->modules->isNotEmpty() && $course->modules->first()->contents->isNotEmpty()) {
            $activeContent = $course->modules->first()->contents->first();
        }
    }

    // 4. Update Progress & Cek Kelulusan
    if ($activeContent) {
        // Update posisi terakhir
        DB::table('course_enrollments')
            ->where('id', $enrollment->id)
            ->update([
                'last_content_id' => $activeContent->id,
                'updated_at' => now()
            ]);

        // [LOGIKA BARU] Cek apakah ini materi terakhir?
        // Cari modul terakhir dan konten terakhir dari kursus ini
        $lastModule = $course->modules->last();
        if ($lastModule) {
            $lastContent = $lastModule->contents->last();
            
            // Jika konten yang dibuka adalah konten terakhir
            if ($lastContent && $activeContent->id == $lastContent->id) {
                // Update status menjadi 'completed'
                DB::table('course_enrollments')
                    ->where('id', $enrollment->id)
                    ->update(['status' => 'completed']);
                
                // Refresh data enrollment agar $isCompleted di bawah jadi true
                $enrollment->status = 'completed';
            }
        }
    }

    // 5. Cek Status Penyelesaian untuk View
    $isCompleted = false;
    if (isset($enrollment->status) && ($enrollment->status == 'completed' || $enrollment->status == 1)) {
        $isCompleted = true;
    }

    return view('user.courses.learning', [
        'title' => $course->name,
        'course' => $course,
        'activeContent' => $activeContent,
        'isCompleted' => $isCompleted
    ]);
}
}