<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Outlet extends Model
{
    use SoftDeletes;

    protected $table = 'tbl_outlet';
    protected $fillable = [
        'name',
        'address',
        'phone_number',
    ];

    public function users()
    {
        return $this->hasMany(User::class, 'outlet_id');
    }

    public function pakets()
    {
        return $this->hasMany(Paket::class, 'outlet_id');
    }

    public function transaksis()
    {
        return $this->hasMany(Transaksi::class, 'outlet_id');
    }
}
