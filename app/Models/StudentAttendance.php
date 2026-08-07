<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StudentAttendance extends Model
{
    protected $table = 'student_attendance_details';

    protected $primaryKey = 'student_attendance_detail_id';

    protected $guarded = ['student_attendance_detail_id'];

    public function student()
    {
        return $this->belongsTo(Student::class, 'nis', 'nis');
    }

    public function attendance()
    {
        return $this->belongsTo(Attendance::class, 'attendance_id');
    }
}
