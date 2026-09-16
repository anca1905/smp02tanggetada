<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    use HasFactory;

    protected $table = 'attendances';

    protected $fillable = [
        'teacher_id',
        'class',
        'session_type',
        'date',
        'start_time',
        'end_time',
        'qr_token',
        'qr_expires_at',
    ];

    protected $casts = [
        'qr_expires_at' => 'datetime',
    ];

    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }

    public function studentAttendances()
    {
        return $this->hasMany(StudentAttendance::class, 'attendance_id');
    }
}
