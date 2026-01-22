<?php

namespace App\Http\Controllers\user;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Course;

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

        $course = Course::where('slug', $slug)
            ->whereHas('enrollments', function ($q) use ($userId) {
                $q->where('user_id', $userId);
            })
            ->with(['modules.contents'])
            ->firstOrFail();

        return view('user.courses.show', compact('course'));
    }

    public function learning($slug)
    {
        $userId = Auth::id();

        $course = Course::where('slug', $slug)
            ->whereHas('enrollments', function ($q) use ($userId) {
                $q->where('user_id', $userId);
            })
            ->with(['modules.contents'])
            ->firstOrFail();

        return view('user.courses.learning', compact('course'));
    }
}