<?php

namespace App\Http\Controllers\superadmin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class Superadmincontroller extends Controller
{

    public function index()
    {

        $admins = User::where('role', 'admin')
                      ->latest()
                      ->get();

        return response()->json([
            'status' => true,
            'message' => 'List Data Admin',
            'data' => $admins
        ], 200);
    }


    public function store(Request $request)
    {
        $validator = $request->validate([
            'username'  => 'required|string|max:50|unique:users,username',
            'email'     => 'required|email|max:100|unique:users,email',
            'password'  => 'required|string|min:8',
            'full_name' => 'required|string|max:100',
            'domisili'  => 'nullable|string|max:100',
            'avatar_url'=> 'nullable|string',
        ]);


        $data = $validator;
        $data['password'] = Hash::make($request->password);


        $data['role'] = 'admin';
        $data['school_id'] = null;
        $data['current_level'] = 0;



        $user = User::create($data);

        return response()->json([
            'status' => true,
            'message' => 'Admin berhasil dibuat',
            'data' => $user
        ], 201);
    }


    public function show(string $id)
    {

        $admin = User::where('id', $id)->where('role', 'admin')->first();

        if (!$admin) {
            return response()->json([
                'status' => false,
                'message' => 'Data Admin tidak ditemukan'
            ], 404);
        }

        return response()->json([
            'status' => true,
            'data' => $admin
        ], 200);
    }


    public function update(Request $request, string $id)
    {

        $admin = User::where('id', $id)->where('role', 'admin')->first();

        if (!$admin) {
            return response()->json([
                'status' => false,
                'message' => 'Data Admin tidak ditemukan'
            ], 404);
        }

        // Validasi
        $validator = $request->validate([

            'username'  => ['string', 'max:50', Rule::unique('users')->ignore($admin->id)],
            'email'     => ['email', 'max:100', Rule::unique('users')->ignore($admin->id)],
            'full_name' => 'string|max:100',
            'domisili'  => 'nullable|string|max:100',
            'password'  => 'nullable|string|min:8',
        ]);


        if ($request->filled('password')) {
            $validator['password'] = Hash::make($request->password);
        } else {
            unset($validator['password']);
        }


        $admin->update($validator);

        return response()->json([
            'status' => true,
            'message' => 'Data Admin berhasil diperbarui',
            'data' => $admin
        ], 200);
    }

    /**

     */
    public function destroy(string $id)
    {
        // Cek data & role
        $admin = User::where('id', $id)->where('role', 'admin')->first();

        if (!$admin) {
            return response()->json([
                'status' => false,
                'message' => 'Data Admin tidak ditemukan'
            ], 404);
        }

        $admin->delete();

        return response()->json([
            'status' => true,
            'message' => 'Admin berhasil dihapus'
        ], 200);
    }
}
