<?php

namespace App\Http\Controllers\superadmin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\School;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class AdminSekolahController extends Controller
{
    /**

     */
    public function index()
    {
        $schoolAdmins = User::with('school')
            ->where('role', 'school_admin')
            ->latest()
            ->get();

        return view('superadmin.school_admin.index', compact('schoolAdmins'));
    }

    /**

     */
    public function create()
    {

        $schools = School::all();

        return view('superadmin.school_admin.create', compact('schools'));
    }

    /**

     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'school_id' => 'required|exists:schools,id',
            'username'  => 'required|string|max:50|unique:users,username',
            'email'     => 'required|email|max:100|unique:users,email',
            'password'  => 'required|string|min:8',
            'full_name' => 'required|string|max:100',
            'domisili'  => 'nullable|string|max:100',
            'avatar_url'=> 'nullable|string',
        ]);

        $validated['password'] = Hash::make($request->password);
        $validated['role'] = 'school_admin';
        $validated['current_level'] = 0;

        User::create($validated);

        // Redirect ke halaman index dengan pesan sukses
        return redirect()->route('admin-sekolah.index')
            ->with('success', 'School Admin berhasil dibuat');
    }

    /**

     */
    public function show($id)
    {
        $user = User::where('id', $id)->where('role', 'school_admin')->firstOrFail();

        return view('superadmin.school_admin.show', compact('user'));
    }

    /**

     */
    public function edit($id)
    {
        $user = User::where('id', $id)->where('role', 'school_admin')->firstOrFail();
        $schools = School::all(); // Untuk dropdown edit

        return view('superadmin.school_admin.edit', compact('user', 'schools'));
    }

    /**

     */
    public function update(Request $request, $id)
    {
        $user = User::where('id', $id)->where('role', 'school_admin')->firstOrFail();

        $validated = $request->validate([
            'school_id' => 'exists:schools,id',
            'username'  => ['string', 'max:50', Rule::unique('users')->ignore($user->id)],
            'email'     => ['email', 'max:100', Rule::unique('users')->ignore($user->id)],
            'full_name' => 'string|max:100',
            'domisili'  => 'nullable|string|max:100',
            'password'  => 'nullable|string|min:8',
        ]);

        if ($request->filled('password')) {
            $validated['password'] = Hash::make($request->password);
        } else {
            unset($validated['password']);
        }

        $user->update($validated);

        return redirect()->route('admin-sekolah.index')
            ->with('success', 'Data School Admin berhasil diperbarui');
    }

    /**
     
     */
    public function destroy($id)
    {
        $user = User::where('id', $id)->where('role', 'school_admin')->firstOrFail();

        $user->delete();

        return redirect()->route('admin-sekolah.index')
            ->with('success', 'School Admin berhasil dihapus');
    }
}
