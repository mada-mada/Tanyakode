<?php

namespace App\Http\Controllers\Adminsekolah;

use App\Http\Controllers\Controller;
use App\Models\Course; // Pastikan import Course
use App\Models\Module;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminSekolahModuleController extends Controller
{
    /**
     * Menyimpan Bab (Module) baru ke dalam Kursus.
     */
    public function store(Request $request, Course $course) // Ubah menjadi Course $course
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        // Cek akses: Kursus harus milik sekolah admin tersebut
        if ($user->role === 'school_admin' && $course->school_id !== $user->school_id) {
            abort(403, 'Akses ditolak: Kursus ini bukan milik sekolah Anda.');
        }

        $request->validate(['title' => 'required|string|max:255']);

        // Simpan sebagai Module (Bab)
        Module::create([
            'course_id' => $course->id,
            'title' => $request->title,
            'sort_order' => ($course->modules()->max('sort_order') ?? 0) + 1
        ]);

        return redirect()->back()->with('success', 'Bab berhasil ditambahkan.');
    }

    public function edit(Module $module)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        if ($user->role === 'school_admin' && $module->course->school_id !== $user->school_id) {
            abort(403);
        }
        return view('admin.modules.edit', compact('module'));
    }

    public function update(Request $request, Module $module)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        if ($user->role === 'school_admin' && $module->course->school_id !== $user->school_id) {
            abort(403);
        }

        $request->validate(['title' => 'required|string|max:255']);
        $module->update($request->only('title'));

        return redirect()->route('courses.show', $module->course_id)->with('success', 'Bab diperbarui.');
    }

    public function destroy(Module $module)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        if ($user->role === 'school_admin' && $module->course->school_id !== $user->school_id) {
            abort(403);
        }

        $module->delete();
        return redirect()->back()->with('success', 'Bab berhasil dihapus.');
    }
}