<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Operator extends Authenticatable
{
    use Notifiable, HasFactory;

    protected $table = 'operators';

    protected $primaryKey = 'operator_id';

    protected $fillable = [
        'name',
        'username',
        'password',
        'role',
        'photo_url'
    ];

    protected $hidden = ['password'];
}
