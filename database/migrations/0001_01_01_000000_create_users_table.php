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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->integer('school_id')->nullable();
            $table->enum('role', ['super_admin','admin','school_admin','student']);
            $table->string('username',50)->unique();
            $table->string('email',100)->unique();
            $table->string('password');
            $table->string('full_name',100);
            $table->string('nis',100)->nullable();
            $table->string('nisn',100)->nullable();
            $table->enum('grade',['1','2','3'])->nullable();
            $table->string('school_name',100)->nullable();
            $table->enum('school_category',['SMP','SMA'])->nullable();
            $table->string('domisili',100)->nullable();
            $table->integer('current_level');
            $table->string('avatar_url',100)->nullable();
            $table->timestamp('created_at')->default(1);
            $table->timestamp('updated_at');
        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};
