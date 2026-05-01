<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Paket extends Model
{
    use SoftDeletes;

    protected $table = 'tbl_paket';

    protected $fillable = [
        'outlet_id',
        'jenis',
        'nama_paket',
        'harga'
    ];

    public function outlet()
    {
        return $this->belongsTo(Outlet::class, 'outlet_id');
    }

    public function details()
    {
        return $this->hasMany(DetailTransaksi::class, 'paket_id');
    }
}
