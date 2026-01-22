<?php

namespace App\Models;

use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\School;
use App\Models\Order;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'username',
        'role',
        'email',
        'password',
        'full_name',
        'nis',
        'nisn',
        'grade',
        'school_name',
        'school_id',
        'school_category',
        'domisili',
        'current_level',
        'avatar_url',
        'status',
        'created_at',
        'updated_at',
        'nama_lengkap',
        'sekolah',
        'alamat',
        'no_hp',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function school()
    {
        return $this->belongsTo(School::class, 'school_id', 'id');
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function hasPurchased($courseId)
    {
        return $this->orders()
            ->where('course_id', $courseId)
            ->where('payment_status', 'settlement')
            ->exists();
    }

    // FIX: Tambahkan method untuk enrollment dan courses
    public function enrollments()
    {
        return $this->hasMany(\App\Models\CourseEnrollment::class);
    }

    public function courses()
    {
        return $this->belongsToMany(\App\Models\Course::class, 'course_enrollments')
            ->withPivot('progress_percentage', 'status', 'completed_at')
            ->withTimestamps();
    }
}