<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Module;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class ModuleController extends Controller
{
    // create biasanya digabung di halaman show Course (Modal/Form)
    public function store(Request $request, Course $course)
    {
        /** @var User $user */
        $user = Auth::user();
        if ($user->role === 'school_admin' && $course->school_id !== $user->school_id) abort(403);

        $request->validate(['title' => 'required|string|max:255']);

        Module::create([
            'course_id' => $course->id,
            'title' => $request->title,
            'sort_order' => ($course->modules()->max('sort_order') ?? 0) + 1
        ]);

        return redirect()->back()->with('success', 'Bab berhasil ditambahkan.');
    }

    public function edit(Module $module)
    {
        /** @var User $user */
        $user = Auth::user();
        if ($user->role === 'school_admin' && $module->course->school_id !== $user->school_id) abort(403);

        return view('admin.modules.edit', compact('module'));
    }

    public function update(Request $request, Module $module)
    {
        /** @var User $user */
        $user = Auth::user();
        if ($user->role === 'school_admin' && $module->course->school_id !== $user->school_id) abort(403);

        $request->validate(['title' => 'required|string|max:255']);
        $module->update($request->only('title'));

        return redirect()->route('courses.show', $module->course_id)->with('success', 'Bab diperbarui.');
    }

    public function destroy(Module $module)
    {
        /** @var User $user */
        $user = Auth::user();
        if ($user->role === 'school_admin' && $module->course->school_id !== $user->school_id) abort(403);

        $module->delete();
        return redirect()->back()->with('success', 'Bab dihapus.');
    }
}
