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

        // Mengembalikan View: resources/views/superadmin/sekolah/index.blade.php
        return view('superadmin.sekolah.index', compact('schools'));
    }

    /**

     */
    public function create()
    {
        // Mengembalikan View: resources/views/superadmin/sekolah/create.blade.php
        return view('superadmin.sekolah.create');
    }

    /**

     */
    public function store(Request $request)
    {
        $validator = $request->validate([
            'npsn'      => 'required|string|max:50|unique:schools,npsn',
            'name'      => 'required|string|max:255',
            'address'   => 'required|string',
            // Jika nanti 'logo_url' berupa upload file, ubah validasi ini menjadi 'image|max:2048'
            'logo_url'  => 'required|string',
        ]);

        $data = $validator;

        // Set default value sesuai logika bisnis Anda
        $data['subscription_status'] = 'active';
        $data['is_token_active'] = 1;

        School::create($data);

        return redirect()->route('sekolah.index')
            ->with('success', 'Sekolah berhasil ditambahkan');
    }

    /**

     */
    public function show($id)
    {
        $school = School::findOrFail($id);


        return view('superadmin.sekolah.show', compact('school'));
    }

    /**

     */
    public function edit($id)
    {
        $school = School::findOrFail($id);


        return view('superadmin.sekolah.edit', compact('school'));
    }

    /**

     */
    public function update(Request $request, $id)
    {
        $school = School::findOrFail($id);

        $validator = $request->validate([
            'npsn'      => ['required', 'string', 'max:50', Rule::unique('schools')->ignore($school->id)],
            'name'      => 'required|string|max:255',
            'address'   => 'required|string',
            'logo_url'  => 'required|string',
            'token_code'          => 'nullable|string',
            'is_token_active'     => 'boolean', // Menerima 0 atau 1
            'subscription_status' => 'in:active,inactive',
        ]);

        $school->update($validator);

        return redirect()->route('sekolah.index')
            ->with('success', 'Data Sekolah berhasil diperbarui');
    }

    /**
     
     */
    public function destroy($id)
    {
        $school = School::findOrFail($id);

        $school->delete();

        return redirect()->route('sekolah.index')
            ->with('success', 'Sekolah berhasil dihapus');
    }
}
