<?php

namespace App\Http\Controllers\Adminsekolah;

use App\Http\Controllers\Controller;
use App\Models\Module;
use App\Models\ModuleContent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class AdminSekolahModuleContentController extends Controller
{
    public function store(Request $request, Module $module)
{
    /** @var User $user */
    $user = Auth::user();
    if ($user->role === 'school_admin' && $module->course->school_id !== $user->school_id) abort(403);

    $validated = $request->validate([
        'title' => 'required|string|max:255',
        'type' => 'required|in:theory,practice', // Pastikan ini 'type' sesuai database
        'content_body' => 'required',
        'video_url' => 'nullable|url',
        'compiler_lang' => 'required_if:type,practice|nullable', // Ganti content_type jadi type
        'practice_snippet' => 'nullable|string', // Pastikan nama ini sesuai kolom database
    ]);

    $validated['module_id'] = $module->id;
    $validated['sort_order'] = ($module->contents()->max('sort_order') ?? 0) + 1;

    // Proses Simpan
    ModuleContent::create($validated);
    
    return redirect()->back()->with('success', 'Konten materi berhasil ditambahkan.');
}

    public function edit(ModuleContent $content)
    {
        /** @var User $user */
        $user = Auth::user();
        if ($user->role === 'school_admin' && $content->module->course->school_id !== $user->school_id) abort(403);

        return view('admin.contents.edit', compact('content'));
    }

   public function update(Request $request, ModuleContent $content)
{
    /** @var User $user */
    $user = Auth::user();
    if ($user->role === 'school_admin' && $content->module->course->school_id !== $user->school_id) abort(403);

    $validated = $request->validate([
        'title' => 'required|string|max:255',
        'type' => 'required|in:theory,practice',
        'content_body' => 'required',
        'video_url' => 'nullable|url',
        
        // Hilangkan 'nullable' agar validasi 'required_if' bekerja maksimal
        'compiler_lang' => 'required_if:type,practice', 
        'practice_snippet' => 'required_if:type,practice|string', 
    ]);

    $content->update($validated);
    
    return redirect()->route('courses.show', $content->module->course_id)
                     ->with('success', 'Konten diperbarui.');
}
    public function destroy(ModuleContent $content)
    {
        /** @var User $user */
        $user = Auth::user();
        if ($user->role === 'school_admin' && $content->module->course->school_id !== $user->school_id) abort(403);

        $content->delete();
        return redirect()->back()->with('success', 'Konten dihapus.');
    }
}