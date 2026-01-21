<?php

namespace App\Http\Controllers\user;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Course;

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

    public function learning($slug)
    {
        $course = Course::with(['modules.contents'])->find($slug);
        
        if (!$course) {
            $course = Course::with(['modules.contents'])->where('slug', $slug)->firstOrFail();
        }

        return view('user.courses.learning', compact('course'));
    }
}