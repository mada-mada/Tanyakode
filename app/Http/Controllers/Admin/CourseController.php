<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Str;

class CourseController extends Controller
{
    public function index()
    {
        /** @var User $user */
        $user = Auth::user();

        $courses = Course::when($user->role === 'school_admin', function ($query) use ($user) {
            return $query->where('school_id', $user->school_id);
        })->when($user->role === 'admin', function ($query) {
            return $query->whereNull('school_id');
        })->get();

        return view('admin.courses.index', compact('courses'));
    }

    public function create()
    {
        return view('admin.courses.create');
    }

    public function store(Request $request)
    {
        /** @var User $user */
        $user = Auth::user();

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'level' => 'required|in:pemula,menengah,expert',
            'price' => 'required_if:level,menengah,expert|nullable|numeric|min:0',
            'has_merchandise_reward' => 'required|boolean',
            'merchandise_name' => 'required_if:has_merchandise_reward,1|nullable|string',
        ]);

        if ($validated['level'] === 'pemula') {
            $validated['is_premium'] = 0;
            $validated['price'] = 0;
        } else {
            $validated['is_premium'] = 1;
        }

        $validated['slug'] = Str::slug($validated['title']);

        $validated['school_id'] = $user->school_id;
        $validated['created_by'] = $user->id;

        Course::create($validated);
        return redirect()->route('courses.index')->with('success', 'Kursus berhasil dibuat.');
    }

    public function show(Course $course)
    {
        // Load modules dan contents untuk ditampilkan di detail
        $course->load(['modules.contents']);
        return view('admin.courses.show', compact('course'));
    }

    public function edit(Course $course)
    {
        /** @var User $user */
        $user = Auth::user();
        if ($user->role === 'school_admin' && $course->school_id !== $user->school_id) abort(403);

        return view('admin.courses.edit', compact('course'));
    }

    public function update(Request $request, Course $course)
    {
        /** @var User $user */
        $user = Auth::user();
        if ($user->role === 'school_admin' && $course->school_id !== $user->school_id) abort(403);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'level' => 'required|in:pemula,menengah,expert',
            'price' => 'required_if:level,menengah,expert|nullable|numeric|min:0',
            'has_merchandise_reward' => 'required|boolean',
            'merchandise_name' => 'required_if:has_merchandise_reward,1|nullable|string',
        ]);

        if ($validated['level'] === 'pemula') {
            $validated['is_premium'] = 0;
            $validated['price'] = 0;
        } else {
            $validated['is_premium'] = 1;
        }

        $validated['slug'] = Str::slug($validated['title']);


        $course->update($validated);
        return redirect()->route('courses.index')->with('success', 'Kursus berhasil diperbarui.');
    }

    public function destroy(Course $course)
    {
        /** @var User $user */
        $user = Auth::user();
        if ($user->role === 'school_admin' && $course->school_id !== $user->school_id) abort(403);

        $course->delete();
        return redirect()->route('courses.index')->with('success', 'Kursus dihapus.');
    }

    /**
     * Menampilkan Halaman Dashboard Admin.
     */
    public function dashboard()
    {
        $user = Auth::user();

        // Hitung kursus berdasarkan role
        $courseQuery = Course::query();

        if ($user->role === 'school_admin') {
            $courseQuery->where('school_id', $user->school_id);
        } else if ($user->role === 'admin') {
            $courseQuery->whereNull('school_id');
        }

        $totalCourses = $courseQuery->count();

        // Kirim data ke view dashboard yang baru kita buat
        return view('admin.dashboard', compact('totalCourses'));
    }
}

