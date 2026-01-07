<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContentProgress extends Model
{
    protected $table = 'content_progress';

    protected $fillable = [
        'enrollment_id',
        'content_id',
        'is_completed',
        'completed_at',
    ];

    public $timestamps = false;

    public function enrollment()
    {
        return $this->belongsTo(CourseEnrollment::class, 'enrollment_id');
    }

    public function content()
    {
        return $this->belongsTo(ModuleContent::class, 'content_id');
    }
}
