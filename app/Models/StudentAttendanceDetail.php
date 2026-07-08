<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Alias model untuk tabel student_attendance_details.
 * Dipakai oleh StudentApiController untuk API mobile siswa.
 */
class StudentAttendanceDetail extends Model
{
    protected $table = 'student_attendance_details';

    protected $fillable = [
        'attendance_id',
        'student_id',
        'status',
    ];

    public function attendance()
    {
        return $this->belongsTo(Attendance::class, 'attendance_id');
    }

    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id');
    }
}
