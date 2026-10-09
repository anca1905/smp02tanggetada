<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Teacher extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

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

    /**
     * Accessor untuk NIP/ID pegawai.
     */
    public function getNipAttribute(): ?string
    {
        return $this->employee_id ?: (string) $this->id;
    }

    /**
     * Accessor untuk nama kelas wali.
     */
    public function getWaliKelasNameAttribute(): ?string
    {
        return $this->classroom?->name ?? ($this->homeroom_class && $this->homeroom_class !== '-' ? $this->homeroom_class : null);
    }
}
