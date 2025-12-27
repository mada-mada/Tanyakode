<?php

namespace App\Http\Controllers\superadmin;

use App\Http\Controllers\Controller;
use App\Models\School;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class superadmin_sekolahcontroller extends Controller
{
    /**

     */
    public function index()
    {
        $schools = School::latest()->get();

        return response()->json([
            'status' => true,
            'message' => 'List Data Sekolah',
            'data' => $schools
        ], 200);
    }

    /**

     */
    public function store(Request $request)
    {

        $validator = $request->validate([
            'npsn'      => 'required|string|max:50|unique:schools,npsn',
            'name'      => 'required|string|max:255',
            'address'   => 'required|string',
            'logo_url'  => 'required|string',
        ]);


        $data = $validator;

        $data['subscription_status'] = 'active';


        $data['is_token_active'] = 1;

        $school = School::create($data);

        return response()->json([
            'status' => true,
            'message' => 'Sekolah berhasil ditambahkan',
            'data' => $school
        ], 201);
    }

    /**

     */
    public function show($id)
    {
        $school = School::find($id);

        if (!$school) {
            return response()->json(['message' => 'Sekolah tidak ditemukan'], 404);
        }

        return response()->json([
            'status' => true,
            'data' => $school
        ], 200);
    }

    /**

     */
    public function update(Request $request, $id)
    {
        $school = School::find($id);

        if (!$school) {
            return response()->json(['message' => 'Sekolah tidak ditemukan'], 404);
        }

        $validator = $request->validate([
            'npsn'      => ['required', 'string', 'max:50', Rule::unique('schools')->ignore($school->id)],
            'name'      => 'required|string|max:255',
            'address'   => 'required|string',
            'logo_url'  => 'required|string',


            'token_code'          => 'nullable|string',
            'is_token_active'     => 'boolean', // 0 atau 1
            'subscription_status' => 'in:active,inactive',
        ]);

        $school->update($validator);

        return response()->json([
            'status' => true,
            'message' => 'Data Sekolah berhasil diperbarui',
            'data' => $school
        ], 200);
    }

    /**

     */
    public function destroy($id)
    {
        $school = School::find($id);

        if (!$school) {
            return response()->json(['message' => 'Sekolah tidak ditemukan'], 404);
        }

        $school->delete();

        return response()->json([
            'status' => true,
            'message' => 'Sekolah berhasil dihapus'
        ], 200);
    }
}
