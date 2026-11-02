<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Extracurricular extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'category',
        'instructor_name',
        'schedule_day',
        'location',
        'photo_path',
        'description',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function registrations(): HasMany
    {
        return $this->hasMany(ExtracurricularRegistration::class, 'extracurricular_id');
    }

    public function acceptedRegistrations(): HasMany
    {
        return $this->hasMany(ExtracurricularRegistration::class, 'extracurricular_id')->where('status', 'accepted');
    }

    public function getCategoryLabelAttribute(): string
    {
        return match ($this->category) {
            'wajib' => 'Wajib (Kepanduan)',
            'olahraga' => 'Olahraga & Prestasi',
            'seni_budaya' => 'Seni & Budaya',
            'keagamaan' => 'Keagamaan / Rohis',
            'akademik' => 'Klub Akademik / OSN',
            'pilihan' => 'Pilihan Umum',
            default => 'Lainnya',
        };
    }
}
