<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
   // database/migrations/xxxx_xx_xx_create_spin_rewards_table.php
public function up()
{
    Schema::create('spin_rewards', function (Blueprint $table) {
        $table->id();
        $table->string('name'); // Nama hadiah (misal: Diskon 50%, Zonk, dll)
        $table->string('type'); // 'voucher' atau 'zonk' (kosong)
        $table->decimal('voucher_amount', 10, 2)->nullable(); // Nominal diskon jika voucher
        $table->integer('probability'); // Persentase kemungkinan (0 - 100)
        $table->string('color'); // Warna segmen di roda
        $table->boolean('is_active')->default(true);
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('spin_rewards');
    }
};
