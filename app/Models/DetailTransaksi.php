<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DetailTransaksi extends Model
{
    use SoftDeletes;

    protected $table = 'tbl_detail_transaksi';
    protected $fillable = [
        'transaksi_id', 
        'paket_id', 
        'qty', 
        'keterangan'
    ];

    public function transaksi() 
    { 
        return $this->belongsTo(Transaksi::class, 'transaksi_id'); 
    }

    public function paket()     
    { 
        return $this->belongsTo(Paket::class, 'paket_id'); 
    }
}
