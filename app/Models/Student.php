<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    protected $table = 'students';
    protected $primaryKey = 'nis';
    public $incrementing = false; 
    protected $keyType = 'string';

    protected $fillable = [
        'nis', 'student_name', 'gender', 'class', 'phone_number', 'student_status'
    ];
}