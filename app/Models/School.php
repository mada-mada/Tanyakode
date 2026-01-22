<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class School extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'npsn',
        'name',
        'address',
        'token_code',
        'is_token_active',
        'subscription_status',
    ];


    protected $casts = [
        'is_token_active' => 'boolean',
    ];
    public function users()
      {
     // Mengacu pada tabel users yang memiliki kolom school_id
    return $this->hasMany(User::class, 'school_id');
        }
}
