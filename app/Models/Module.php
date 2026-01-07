<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Course;
use App\Models\ModuleContent;

class Module extends Model
{
    protected $fillable = [
        'course_id',
        'title',
        'sort_order'
    ];

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function contents()
    {
        // Otomatis mengurutkan konten saat dipanggil
        return $this->hasMany(ModuleContent::class)->orderBy('sort_order', 'asc');
    }
}
