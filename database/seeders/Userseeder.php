<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class UserSeeder extends Seeder
{
    public function run()
    {

        // 4. STUDENT (Dilengkapi data siswa: NIS, NISN, Grade)
        User::create([
            'school_id'     => null,
            'username'      => 'budi_siswa',
            'email'         => 'student@tanyakode.com',
            'password'      => Hash::make('password123'),
            'full_name'     => 'Budi Santoso',
            'role'          => 'student',
            'nis'           => '123456',
            'nisn'          => '00987654321',
            'grade'         => '1', // Sesuai enum ['1','2','3']
            'school_name'   => 'SMA Negeri 1 Jakarta',
            'school_category'=> 'SMA',
            'domisili'      => 'Jakarta Selatan',
            'current_level' => 1,
            'avatar_url'    => 'default_avatar.png',
            'created_at'    => Carbon::now(),
            'updated_at'    => Carbon::now(),
        ]);
    }
}
