<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Outlet extends Model
{
    protected $table = 'tbl_outlet';
    protected $fillable = [
        'name',
        'address',
        'phone_number',
    ];

    public function users ()
    {
        return $this->hasMany(User::class, 'outlet_id');
    }
}
