<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Member extends Model
{
    use SoftDeletes;

    protected $table = 'tbl_member';
    protected $fillable = [
        'name',
        'address',
        'gender',
        'phone_number',
    ];

    public function transaksis()
    {
        return $this->hasMany(Transaksi::class, 'member_id');
    }
}
