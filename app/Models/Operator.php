<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Operator extends Authenticatable
{
    use Notifiable;

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
