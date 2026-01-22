<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class CourseController extends Controller
{
    public function index()
    {
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
        $user = Auth::user();

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string', // Tambahan deskripsi
            'level' => 'required|in:pemula,menengah,expert',
            'price' => 'required_if:level,menengah,expert|nullable|numeric|min:0',
            'has_merchandise_reward' => 'required|boolean',
            'merchandise_name' => 'required_if:has_merchandise_reward,1|nullable|string',
            'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', 
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

        if ($request->hasFile('thumbnail')) {
            $path = $request->file('thumbnail')->store('thumbnails', 'public');
            $validated['thumbnail_url'] = $path;
        }

        Course::create($validated);

        return redirect()->route('courses.index')->with('success', 'Kursus berhasil dibuat.');
    } // Kurung kurawal tutup yang tadi hilang sudah ditambahkan di sini

    public function edit(Course $course)
    {
        $user = Auth::user();
        
        if ($user->role === 'school_admin' && $course->school_id !== $user->school_id) {
            abort(403);
        }

        return view('admin.courses.edit', compact('course'));
    }

    public function update(Request $request, Course $course)
    {
        $user = Auth::user();
        if ($user->role === 'school_admin' && $course->school_id !== $user->school_id) abort(403);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string', // Tambahan deskripsi
            'level' => 'required|in:pemula,menengah,expert',
            'price' => 'required_if:level,menengah,expert|nullable|numeric|min:0',
            'has_merchandise_reward' => 'required|boolean',
            'merchandise_name' => 'required_if:has_merchandise_reward,1|nullable|string',
            'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($validated['level'] === 'pemula') {
            $validated['is_premium'] = 0;
            $validated['price'] = 0;
        } else {
            $validated['is_premium'] = 1;
        }

        $validated['slug'] = Str::slug($validated['title']);

        // Logika Update Thumbnail: Hapus yang lama jika ada yang baru
        if ($request->hasFile('thumbnail')) {
            if ($course->thumbnail_url && Storage::disk('public')->exists($course->thumbnail_url)) {
                Storage::disk('public')->delete($course->thumbnail_url);
            }
            $path = $request->file('thumbnail')->store('thumbnails', 'public');
            $validated['thumbnail_url'] = $path;
        }

        $course->update($validated);
        return redirect()->route('courses.index')->with('success', 'Kursus berhasil diperbarui.');
    }

    public function destroy(Course $course)
    {
        $user = Auth::user();
        if ($user->role === 'school_admin' && $course->school_id !== $user->school_id) abort(403);

        if ($course->thumbnail_url && Storage::disk('public')->exists($course->thumbnail_url)) {
            Storage::disk('public')->delete($course->thumbnail_url);
        }

        $course->delete();
        return redirect()->route('courses.index')->with('success', 'Kursus dihapus.');
    }

    public function dashboard()
    {
        $user = Auth::user();
        $courseQuery = Course::query();

        if ($user->role === 'school_admin') {
            $courseQuery->where('school_id', $user->school_id);
        } else if ($user->role === 'admin') {
            $courseQuery->whereNull('school_id');
        }

        $totalCourses = $courseQuery->count();
        return view('admin.dashboard', compact('totalCourses'));
    }

    public function show(Course $course)
{
    // Memuat relasi modul dan konten materi yang ada di database
    $course->load(['modules.contents']);
    
    // Pastikan file view ini sudah Anda buat
    return view('admin.courses.show', compact('course'));
}
}