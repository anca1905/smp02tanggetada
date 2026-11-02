<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
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

    public function counselingRecords(): HasMany
    {
        return $this->hasMany(StudentCounselingRecord::class, 'student_id');
    }

    public function achievements(): HasMany
    {
        return $this->hasMany(Achievement::class, 'student_id');
    }

    public function graduation(): HasOne
    {
        return $this->hasOne(Graduation::class, 'student_id');
    }

    public function libraryLoans(): HasMany
    {
        return $this->hasMany(LibraryLoan::class, 'student_id');
    }

    public function extracurricularRegistrations(): HasMany
    {
        return $this->hasMany(ExtracurricularRegistration::class, 'student_id');
    }

    // public function submissions()
    // {
    //     return $this->hasMany(Submission::class);
    // }
}
