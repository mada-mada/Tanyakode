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
        Schema::create('spin_logs', function (Blueprint $table) {
            $table->id();
            
            // 1. Kolom User ID (Wajib: untuk tahu siapa yang main)
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            // 2. Kolom Reward ID (Wajib: untuk tahu dapat hadiah apa)
            $table->foreignId('spin_reward_id')->constrained('spin_rewards')->onDelete('cascade');
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('spin_logs');
    }
};
