<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Teacher extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'teachers';

    protected $fillable = [
        'name',
        'gender',
        'employee_id',
        'phone',
        'subject',
        'homeroom_class',
        'position',
        'status',
        'username',
        'password',
        'photo_url',
    ];

    protected $hidden = ['password'];

    // Relasi presensi guru dihapus — sekolah tidak menggunakan presensi guru

    public function schedules()
    {
        return $this->hasMany(Schedule::class);
    }

    public function classroom()
    {
        return $this->hasOne(Classroom::class);
    }
}
