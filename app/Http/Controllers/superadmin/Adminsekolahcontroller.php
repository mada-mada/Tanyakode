<?php

namespace App\Http\Controllers\superadmin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class Adminsekolahcontroller extends Controller
{
    /**

     */
    public function index()
    {

        $schoolAdmins = User::where('role', 'school_admin')
                            ->with('school')
                            ->latest()
                            ->get();

        return response()->json([
            'status' => true,
            'message' => 'List Data School Admin',
            'data' => $schoolAdmins
        ], 200);
    }

    /**

     */
    public function store(Request $request)
    {
        // 1. Validasi
        $validator = $request->validate([
            'school_id' => 'required|exists:schools,id',
            'username'  => 'required|string|max:50|unique:users,username',
            'email'     => 'required|email|max:100|unique:users,email',
            'password'  => 'required|string|min:8',
            'full_name' => 'required|string|max:100',
            'domisili'  => 'nullable|string|max:100',
            'avatar_url'=> 'nullable|string',
        ]);

        // 2. Siapkan Data
        $data = $validator;
        $data['password'] = Hash::make($request->password);


        $data['role'] = 'school_admin';
        $data['current_level'] = 0;




        $user = User::create($data);

        return response()->json([
            'status' => true,
            'message' => 'School Admin berhasil dibuat',
            'data' => $user
        ], 201);
    }

    /**

     */
    public function show($id)
    {
        $user = User::with('school')
                    ->where('id', $id)
                    ->where('role', 'school_admin')
                    ->first();

        if (!$user) {
            return response()->json([
                'status' => false,
                'message' => 'Data School Admin tidak ditemukan'
            ], 404);
        }

        return response()->json([
            'status' => true,
            'data' => $user
        ], 200);
    }

    /**

     */
    public function update(Request $request, $id)
    {
        $user = User::where('id', $id)->where('role', 'school_admin')->first();

        if (!$user) {
            return response()->json(['message' => 'User tidak ditemukan'], 404);
        }


        $validator = $request->validate([
            'school_id' => 'exists:schools,id',
            'username'  => ['string', 'max:50', Rule::unique('users')->ignore($user->id)],
            'email'     => ['email', 'max:100', Rule::unique('users')->ignore($user->id)],
            'full_name' => 'string|max:100',
            'domisili'  => 'nullable|string|max:100',
            'password'  => 'nullable|string|min:8',
        ]);

        // Cek Password
        if ($request->filled('password')) {
            $validator['password'] = Hash::make($request->password);
        } else {
            unset($validator['password']);
        }

        $user->update($validator);

        return response()->json([
            'status' => true,
            'message' => 'Data School Admin berhasil diperbarui',
            'data' => $user
        ], 200);
    }

    /**

     */
    public function destroy($id)
    {
        $user = User::where('id', $id)->where('role', 'school_admin')->first();

        if (!$user) {
            return response()->json(['message' => 'User tidak ditemukan'], 404);
        }

        $user->delete();

        return response()->json([
            'status' => true,
            'message' => 'School Admin berhasil dihapus'
        ], 200);
    }
}
