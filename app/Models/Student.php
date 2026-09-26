<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Override;

class Student extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'nis',
        'nisn',
        'student_name',
        'password',
        'gender',
        'classroom_id',
        'phone_number',
        'student_status',
        'photo_url',
        'parent_name',
        'parent_phone',
        'parent_password',
    ];

    protected $hidden = ['password', 'parent_password', 'remember_token'];

    public function classroom()
    {
        return $this->belongsTo(Classroom::class);
    }

    #[Override]
    public function getRouteKeyName()
    {
        return 'nis';
    }

    public function getGenderDisplayAttribute(): string
    {
        return $this->gender === 'M' ? 'Laki-laki' : ($this->gender === 'F' ? 'Perempuan' : '-');
    }

    // public function submissions()
    // {
    //     return $this->hasMany(Submission::class);
    // }
}
