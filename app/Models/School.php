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
        'logo_url',
        'token_code',
        'is_token_active',
        'subscription_status',
    ];


    protected $casts = [
        'is_token_active' => 'boolean',
    ];
}
