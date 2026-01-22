<?php

namespace App\Http\Controllers\Adminsekolah;

use App\Http\Controllers\Controller;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class AdminSekolahCourseController extends Controller
{
    /**
     * Menampilkan daftar kursus.
     * School Admin: Hanya kursus milik sekolahnya.
     * Admin Global: Hanya kursus umum (school_id null).
     */
   public function index(Request $request)
{
    $user = Auth::user();
    $search = $request->query('search');

    // Query dasar dengan filter role yang sudah ada
    $courses = Course::query()
        ->when($user->role === 'school_admin', function ($query) use ($user) {
            return $query->where('school_id', $user->school_id);
        })
        ->when($user->role === 'admin', function ($query) {
            return $query->whereNull('school_id');
        })
        // Tambahkan logika pencarian berdasarkan judul
        ->when($search, function ($query) use ($search) {
            return $query->where('title', 'like', "%{$search}%");
        })
        ->latest()
        ->get();

    return view('adminsekolah.courses.index', compact('courses'));
}

    public function create()
    {
        return view('adminsekolah.courses.create');
    }

    /**
     * Menyimpan kursus baru.
     */
    public function store(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'level' => 'required|in:pemula,menengah,expert',
            'price' => 'required_if:level,menengah,expert|nullable|numeric|min:0',
            'has_merchandise_reward' => 'required|boolean',
            'merchandise_name' => 'required_if:has_merchandise_reward,1|nullable|string',
            'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', 
        ]);

        // Logika penentuan status premium dan harga berdasarkan level
        if ($validated['level'] === 'pemula') {
            $validated['is_premium'] = 0;
            $validated['price'] = 0;
        } else {
            $validated['is_premium'] = 1;
        }

        $validated['slug'] = Str::slug($validated['title']);
        
        // Otomatis mengisi school_id dan created_by berdasarkan user login
        $validated['school_id'] = $user->school_id;
        $validated['created_by'] = $user->id;

        // Proses upload gambar thumbnail
        if ($request->hasFile('thumbnail')) {
            $path = $request->file('thumbnail')->store('thumbnails', 'public');
            $validated['thumbnail_url'] = $path;
        }

        Course::create($validated);

        return redirect()->route('courses.index')->with('success', 'Kursus berhasil dibuat.');
    }

    /**
     * Menampilkan detail kursus beserta modul dan materinya.
     */
    public function show(Course $course)
    {
        $user = Auth::user();
        // Proteksi akses jika school_admin mencoba melihat kursus sekolah lain
        if ($user->role === 'school_admin' && $course->school_id !== $user->school_id) abort(403);

        $course->load(['modules.contents']);
        return view('admin.courses.show', compact('course'));
    }

    public function edit(Course $course)
    {
        $user = Auth::user();
        if ($user->role === 'school_admin' && $course->school_id !== $user->school_id) abort(403);

        return view('admin.courses.edit', compact('course'));
    }

    /**
     * Memperbarui data kursus.
     */
    public function update(Request $request, Course $course)
    {
        $user = Auth::user();
        if ($user->role === 'school_admin' && $course->school_id !== $user->school_id) abort(403);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
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

        // Jika ada upload gambar baru, hapus gambar lama dari storage
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

    /**
     * Menghapus kursus.
     */
    public function destroy(Course $course)
    {
        $user = Auth::user();
        if ($user->role === 'school_admin' && $course->school_id !== $user->school_id) abort(403);

        // Hapus file gambar dari storage sebelum menghapus record database
        if ($course->thumbnail_url && Storage::disk('public')->exists($course->thumbnail_url)) {
            Storage::disk('public')->delete($course->thumbnail_url);
        }

        $course->delete();
        return redirect()->route('courses.index')->with('success', 'Kursus berhasil dihapus.');
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
}