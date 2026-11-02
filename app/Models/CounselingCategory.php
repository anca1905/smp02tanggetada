<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CounselingCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'type',
        'point',
        'description',
    ];

    protected $casts = [
        'point' => 'integer',
    ];

    public function records(): HasMany
    {
        return $this->hasMany(StudentCounselingRecord::class, 'counseling_category_id');
    }
}
