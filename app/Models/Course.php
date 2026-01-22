<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Module;


class Course extends Model
{
    use HasFactory;

    protected $fillable = [
        'school_id',
        'created_by',
        'title',
        'slug',
        'level',
        'price',
        'is_premium',
        'has_merchandise_reward',
        'merchandise_name',
        'thumbnail_url',
        'description'
    ];

    /**
     * Relasi ke Sekolah (NULL jika dari Admin Pusat)
     */
    public function school()
    {
        return $this->belongsTo(School::class, 'school_id');
    }

    /**
     * Relasi ke User pembuat
     */
   public function modules()
{
    // Mengurutkan bab otomatis berdasarkan sort_order
    return $this->hasMany(Module::class)->orderBy('sort_order', 'asc');
}

public function creator()
{
    // Sesuaikan dengan field 'created_by' di PDF database
    return $this->belongsTo(User::class, 'created_by');
}

public function enrollments()
    {
        return $this->hasMany(CourseEnrollment::class, 'course_id');
    }
    
}
