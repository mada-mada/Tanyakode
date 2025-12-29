<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    // Database/Migrations/xxxx_xx_xx_create_otps_table.php

public function up(): void
{
    Schema::create('otps', function (Blueprint $table) {
        $table->id();

        // Relasi ke tabel users
        // onDelete('cascade') berarti jika user dihapus, OTP-nya juga hilang (biar sampah data tidak numpuk)
        $table->foreignId('user_id')->constrained('users')->onDelete('cascade');

        // Kode OTP (String)
        $table->string('otp_code', 6);

        // Tipe OTP (Opsional, tapi sangat berguna jika nanti ada fitur Lupa Password)
        $table->enum('type', ['verification', 'reset_password', 'login'])->default('verification');

        // Waktu Kadaluarsa
        $table->timestamp('expires_at');

        // Kapan dibuat
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
