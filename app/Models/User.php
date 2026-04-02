<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{

    protected $table = 'table_user';
    protected $fillable = [
        'nama',
        'username',
        'password',
        'id_outlet',
        'role'
    ];
}
