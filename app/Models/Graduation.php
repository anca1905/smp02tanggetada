<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class Graduation extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'nisn',
        'nis',
        'student_name',
        'birth_date',
        'birth_place',
        'classroom_name',
        'letter_number',
        'status',
        'average_score',
        'scores_json',
        'graduation_date',
        'verification_code',
        'notes',
    ];

    protected $casts = [
        'birth_date' => 'date',
        'graduation_date' => 'date',
        'scores_json' => 'array',
        'average_score' => 'float',
    ];

    protected static function booted(): void
    {
        static::creating(function (Graduation $model) {
            if (empty($model->verification_code)) {
                $model->verification_code = strtoupper(Str::random(12));
            }
        });
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class, 'student_id');
    }
}
