<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CourseEnrollment extends Model
{
    protected $fillable = [
        'user_id',
        'course_id',
        'progress_percentage',
        'last_accessed_content_id',
        'status',
        'is_paid',
        'enrolled_at',
        'completed_at',
    ];

    public $timestamps = false;

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }
}
