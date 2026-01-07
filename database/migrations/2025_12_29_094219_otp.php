<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**

     */


public function up(): void
{
    Schema::create('otps', function (Blueprint $table) {
        $table->id();

        // Relasi ke tabel users
        // onDelete('cascade') berarti jika user dihapus, OTP-nya juga hilang (biar sampah data tidak numpuk)
        $table->foreignId('user_id')->constrained('users')->onDelete('cascade');

        // Kode OTP (String)
        $table->string('otp_code', 6);

        // Tipe OTP ( fitur Lupa Password,verifitikasi akun,login)
        $table->enum('type', ['verification', 'reset_password', 'login'])->default('verification');

        // Waktu Kadaluarsa
        $table->timestamp('expires_at');


        $table->timestamps();
    });
}

    /**

     */
    public function down(): void
    {
        //
    }
};
