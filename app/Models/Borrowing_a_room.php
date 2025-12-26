<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Borrowing_a_room extends Model
{
    protected $table = 'room_borrowings';

    protected $primaryKey = 'borrowing_id';

    protected $fillable = [
        'full_name',
        'nis',
        'class',
        'phone_number',
        'room_type',
        'borrow_date',
        'activity_description',
        'start_time',
        'end_time',
        'responsible_person',
        'status'
    ];
}
