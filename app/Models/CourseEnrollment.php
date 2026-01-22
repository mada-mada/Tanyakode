<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourseEnrollment extends Model
{
    use HasFactory;

    // Pastikan nama tabel sesuai dengan yang ada di database Anda
    // Berdasarkan controller Anda, namanya adalah 'course_enrollments'
    protected $table = 'course_enrollments';

    // Izinkan semua kolom diisi (atau sesuaikan dengan kebutuhan)
    protected $guarded = ['id'];

    // --- RELASI (Opsional tapi disarankan) ---

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }
}