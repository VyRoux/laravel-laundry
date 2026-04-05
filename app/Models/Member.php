<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Member extends Model
{
    protected $table = 'tbl_member';
    protected $fillable = [
        'name',
        'address',
        'gender',
        'phone_number',
    ];
}
