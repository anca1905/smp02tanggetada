<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Teacher extends Authenticatable
{
    use Notifiable, HasFactory;

    protected $table = 'teachers';

    protected $fillable = [
        'name',
        'gender',
        'employee_id',
        'phone',
        'subject',
        'homeroom_class',
        'status',
        'username',
        'password',
        'photo_url'
    ];

    protected $hidden = ['password'];

    public function presensiHarian()
    {
        return $this->hasMany(Teacher_absence::class, 'teacher_id');
    }

    public function absences()
    {
        return $this->hasMany(Teacher_absence::class, 'teacher_id');
    }

    public function schedules()
    {
        return $this->hasMany(Schedule::class);
    }

    public function classroom()
    {
        return $this->hasOne(Classroom::class); 
    }
}
