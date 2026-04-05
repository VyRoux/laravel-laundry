<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{

    protected $table = 'tbl_user';
    protected $fillable = [
        'name',
        'username',
        'password',
        'outlet_id',
        'role'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    // Membuat relasi dengan tabel outlet
    public function outlet()
    {
        return $this->belongsTo(Outlet::class, 'outlet_id');
    }
}
