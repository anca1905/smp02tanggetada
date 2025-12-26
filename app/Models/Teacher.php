<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Teacher extends Authenticatable
{
    use Notifiable;

    protected $table = 'teachers';
    protected $primaryKey = 'teacher_id';

    protected $fillable = [
        'name',
        'gender',
        'ID',
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
}
