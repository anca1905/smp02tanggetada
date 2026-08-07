<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Teacher_absence extends Model
{
    use HasFactory;

    protected $table = 'teacher_attendances';

    protected $primaryKey = 'teacher_attendance_id';

    protected $guarded = ['teacher_attendance_id'];

    protected static function newFactory()
    {
        return \Database\Factories\TeacherAbsenceFactory::new();
    }

    public function teacher()
    {
        return $this->belongsTo(Teacher::class, 'teacher_id');
    }
}
