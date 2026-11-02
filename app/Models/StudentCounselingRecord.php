<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StudentCounselingRecord extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'counseling_category_id',
        'title',
        'type',
        'point',
        'incident_date',
        'description',
        'action_taken',
        'status',
        'reported_by',
    ];

    protected $casts = [
        'point' => 'integer',
        'incident_date' => 'date',
    ];

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class, 'student_id');
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(CounselingCategory::class, 'counseling_category_id');
    }
}
