<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use HasFactory;

    protected $fillable = [
        'school_id',
        'created_by',
        'title',
        'slug',
        'description',
        'level',
        'price',
        'is_premium',
        'thumbnail_url',
        'has_merchandise_reward',
        'merchandise_name',
    ];

    /* =======================
       RELATIONS
    ======================= */

    // Sekolah pemilik course (nullable)
    public function school()
    {
        return $this->belongsTo(School::class, 'school_id');
    }

    // Creator course (admin / superadmin)
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // Modul dalam course
    public function modules()
    {
        return $this->hasMany(Module::class)->orderBy('sort_order');
    }

    // Enrollment user ke course
    public function enrollments()
    {
        return $this->hasMany(CourseEnrollment::class);
    }

    // User yang mengikuti course
    public function users()
    {
        return $this->belongsToMany(
            User::class,
            'course_enrollments',
            'course_id',
            'user_id'
        );
    }

    // Transaksi pembayaran course
    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }
}
