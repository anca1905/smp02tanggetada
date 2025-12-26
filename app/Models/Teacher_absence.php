<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Teacher_absence extends Model
{
    protected $table = 'teacher_attendances';
    protected $primaryKey = 'teacher_attendance_id';
    protected $guarded = ['teacher_attendance_id'];

    public function teacher()
    {
        return $this->belongsTo(Teacher::class, 'teacher_id');
    }
}
