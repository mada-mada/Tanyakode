<?php

namespace App\Models;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Voucher extends Model
{
    use HasFactory;

    // Izinkan semua kolom diisi (untuk mencegah error Mass Assignment saat create)
    protected $guarded = ['id'];

    // Atau jika ingin lebih spesifik (opsional, pilih salah satu):
    // protected $fillable = [
    //     'code',
    //     'user_id',
    //     'amount',
    //     'is_active',
    // ];

    /**
     * Relasi: Voucher dimiliki oleh satu User
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}