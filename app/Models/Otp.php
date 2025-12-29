<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Otp extends Model
{
    use HasFactory;

    // Pastikan nama tabel benar (jika beda, sesuaikan)
    protected $table = 'otps';

    protected $fillable = [
        'user_id',
        'otp_code',
        'expires_at'
    ];
}
