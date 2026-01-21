<?php

namespace App\Http\Controllers\superadmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\School;

class Superadmin_sekolahcontroller extends Controller
{
    public function index()
    {
        $schools = School::all();
        return view('superadmin.sekolah.index', compact('schools'));
    }

    public function create()
    {
        return view('superadmin.sekolah.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'npsn' => 'required|unique:schools,npsn',
            'name' => 'required|string|max:255',
            'phone' => 'required',
            'address' => 'required',
            'email' => 'nullable|email',
        ]);

        // Mengambil semua input dari form
        $data = $request->all();

        // SET DEFAULT STATUS: Otomatis 'active' saat dibuat
        $data['subscription_status'] = 'active';

        School::create($data);

        return redirect()->route('sekolah.index')->with('success', 'Sekolah berhasil ditambahkan');
    }

    public function edit($id)
    {
        $school = School::findOrFail($id);
        return view('superadmin.sekolah.edit', compact('school'));
    }

    public function update(Request $request, $id)
    {
        $school = School::findOrFail($id);

        $request->validate([
          'npsn' => 'required|unique:schools,npsn',
            'name' => 'required|string|max:255',
            'phone' => 'required',
            'address' => 'required',
            'email' => 'nullable|email',
            'subscription_status' => 'in:active,inactive'
        ]);

        // Kita gunakan $request->all() agar jika Anda menambahkan input status di form edit,
        // ia akan otomatis terupdate. Jika tidak ada input status, status lama tetap aman.
        $school->update($request->all());

        return redirect()->route('sekolah.index')->with('success', 'Data sekolah berhasil diupdate');
    }

    public function destroy($id)
    {
        School::findOrFail($id)->delete();
        return redirect()->route('sekolah.index')->with('success', 'Sekolah berhasil dihapus');
    }
}
