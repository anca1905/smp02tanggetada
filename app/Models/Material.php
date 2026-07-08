<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Material extends Model
{
    protected $fillable = [
        'schedule_id',
        'title',
        'description',
        'type',
        'file_path',
        'file_name'
    ];

    public function schedule()
    {
        return $this->belongsTo(Schedule::class);
    }
}
