<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GuestBook extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'institution',
        'phone',
        'category',
        'meet_with',
        'purpose',
        'visit_date',
        'visit_time',
        'status',
        'notes',
    ];

    protected $casts = [
        'visit_date' => 'date',
    ];
}
