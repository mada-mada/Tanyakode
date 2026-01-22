<?php

namespace App\Http\Controllers\Adminsekolah;

use App\Http\Controllers\Controller;
use App\Models\School;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // <--- TAMBAHKAN INI agar Auth::user() berfungsi

class SchoolTokenController extends Controller
{
    /**
     * Ubah nama fungsi dari generateNewToken menjadi generate
     * Agar sesuai dengan Route::post('/generate-token', [SchoolTokenController::class, 'generate'])
     */
    public function generate() 
    {
        $schoolAdmin = Auth::user(); 
        $school = School::find($schoolAdmin->school_id);

        if (!$school) {
            return back()->with('error', 'Data sekolah tidak ditemukan.');
        }

        $school->update([
            'token_code' => strtoupper(Str::random(8)), 
            'is_token_active' => 1
        ]);

        return back()->with('success', 'Kode unik baru berhasil dibuat: ' . $school->token_code);
    }

    public function index()
    {
        $user = Auth::user();
        // Pastikan relasi 'school' sudah ada di Model User
        $school = $user->school; 
        
        return view('admin.dashboard', compact('school'));
    }
}