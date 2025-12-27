<?php

namespace App\Http\Controllers\superadmin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class superadmincontroller extends Controller
{
    /**

     */
    public function __construct()
    {

    }

    /**

     */
    public function index()
    {

        $admins = User::where('role', 'admin')->latest()->paginate(10);

        return view('superadmin.admins.index', compact('admins'));
    }

    /**

     */
    public function create()
    {
        return view('superadmin.admins.create');
    }

    /**

     */
    public function store(Request $request)
    {

        $validatedData = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed', // Pastikan ada field password_confirmation di view
        ]);


        $validatedData['role'] = 'admin'; // Hardcode role sebagai admin
        $validatedData['password'] = Hash::make($request->password);


        User::create($validatedData);

        return redirect()->route('superadmin.admins.index')
            ->with('success', 'Akun Admin berhasil dibuat.');
    }

    /**

     */
    public function edit(User $admin)
    {
        // Opsional: Pastikan yang diedit benar-benar admin, bukan user lain/superadmin
        if ($admin->role !== 'admin') {
            abort(403, 'Anda hanya dapat mengedit akun Admin.');
        }

        return view('superadmin.admins.edit', compact('admin'));
    }

    /**

     */
    public function update(Request $request, User $admin)
    {

        $rules = [
            'name'  => 'required|string|max:255',
            // Rule unique mengabaikan email milik user yang sedang diedit saat ini
            'email' => ['required', 'email', Rule::unique('users')->ignore($admin->id)],
        ];


        if ($request->filled('password')) {
            $rules['password'] = 'min:8|confirmed';
        }

        $validatedData = $request->validate($rules);


        if ($request->filled('password')) {
            $validatedData['password'] = Hash::make($request->password);
        } else {

            unset($validatedData['password']);
        }


        $admin->update($validatedData);

        return redirect()->route('superadmin.admins.index')
            ->with('success', 'Data Admin berhasil diperbarui.');
    }

    /**
     
     */
    public function destroy(User $admin)
    {
        if ($admin->role !== 'admin') {
            abort(403, 'Hanya akun Admin yang boleh dihapus.');
        }

        $admin->delete();

        return redirect()->route('superadmin.admins.index')
            ->with('success', 'Akun Admin berhasil dihapus.');
    }
}
