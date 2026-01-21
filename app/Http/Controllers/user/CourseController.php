<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Course;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    public function learning($slug)
    {
        // Ambil data course berdasarkan slug
        $course = Course::where('slug', $slug)->firstOrFail();

        // Anda bisa menambahkan logika untuk mengambil modul/materi di sini
        // $modules = $course->modules()->with('contents')->get();

        return view('user.courses.learning', compact('course'));
    }
}