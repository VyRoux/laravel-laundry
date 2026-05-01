<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable
{
    use SoftDeletes;

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

    public function outlet()
    {
        return $this->belongsTo(Outlet::class, 'outlet_id');
    }

    public function transaksis()
    {
        return $this->hasMany(Transaksi::class, 'user_id');
    }
}
