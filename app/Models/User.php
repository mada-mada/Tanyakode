<?php

namespace App\Models;

use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

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
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
        ];
    }

    /* =======================
       RELATIONS
    ======================= */

    // Relasi ke sekolah
    public function school()
    {
        return $this->belongsTo(School::class, 'school_id');
    }

    // Enrollment user ke kursus
    public function enrollments()
    {
        return $this->hasMany(CourseEnrollment::class);
    }

    // Kursus yang diikuti user (shortcut)
    public function courses()
    {
        return $this->belongsToMany(
            Course::class,
            'course_enrollments',
            'user_id',
            'course_id'
        );
    }

    // Riwayat transaksi pembayaran
    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    // Progress konten (opsional, kalau mau direct akses)
    public function contentProgress()
    {
        return $this->hasManyThrough(
            ContentProgress::class,
            CourseEnrollment::class,
            'user_id',
            'enrollment_id',
            'id',
            'id'
        );
    }
}
