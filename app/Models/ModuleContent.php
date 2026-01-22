<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ModuleContent extends Model
{
    protected $fillable = [
        'module_id',
        'type',
        'title',
        'content_body',
        'video_url',
        'practice_snippet',
        'compiler_lang',
        'sort_order'
    ];

    public function module()
    {
        return $this->belongsTo(Module::class);
    }
}