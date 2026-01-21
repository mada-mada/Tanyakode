<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Course;
use Illuminate\Http\Request;

class UserCourseController extends Controller
{
    
     public function index()
    {
        $courses = Course::all();
        return view('user.courses.index', compact('courses'));
    }
    
    public function learning($slug)
    {
        // Ambil data course berdasarkan slug
        $course = Course::where('slug', $slug)->firstOrFail();

        // Anda bisa menambahkan logika untuk mengambil modul/materi di sini
        // $modules = $course->modules()->with('contents')->get();

        return view('user.courses.learning', compact('course'));
    }
}