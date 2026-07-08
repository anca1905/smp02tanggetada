<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Assignment extends Model
{
    use HasFactory;

    protected $fillable = [
        'title', 'description', 'due_date', 'file_path', 'classroom_id', 'subject_id', 'teacher_id'
    ];

    protected $casts = [
        'due_date' => 'datetime',
    ];

    public function classroom() { return $this->belongsTo(Classroom::class); }
    public function subject() { return $this->belongsTo(Subject::class); }
    public function teacher() { return $this->belongsTo(Teacher::class); }
    public function submissions() { return $this->hasMany(AssignmentSubmission::class); }
}
