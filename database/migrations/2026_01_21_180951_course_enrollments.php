<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('course_enrollments', function (Blueprint $table) {
            // 1. Menambahkan kolom last_content_id
            // Tipe unsignedBigInteger (standar ID Laravel)
            // Nullable karena saat baru daftar, belum ada materi yang dibuka
            // After 'course_id' agar posisi kolomnya rapi
            $table->unsignedBigInteger('last_content_id')->nullable()->after('course_id');

            // 2. Menambahkan Foreign Key (Relasi)
            // Ini menghubungkan ke tabel 'module_contents'
            // onDelete('set null'): Jika admin menghapus materi tersebut, 
            // maka progress user tidak error, tapi kembali ke null.
            $table->foreign('last_content_id')
                  ->references('id')
                  ->on('module_contents')
                  ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('course_enrollments', function (Blueprint $table) {
            // Hapus foreign key dulu sebelum menghapus kolom
            // Format array syntax otomatis mencari nama constraint: course_enrollments_last_content_id_foreign
            $table->dropForeign(['last_content_id']);
            
            // Hapus kolom
            $table->dropColumn('last_content_id');
        });
    }
};