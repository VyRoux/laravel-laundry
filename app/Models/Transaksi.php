<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Transaksi extends Model
{
    use SoftDeletes;

    protected $table = 'tbl_transaksi';
    protected $fillable = 
    [
    'outlet_id',
    'kode_invoice', 
    'member_id', 
    'tgl', 
    'batas_waktu', 
    'tgl_bayar', 
    'biaya_tambahan', 
    'diskon', 
    'pajak', 
    'status', 
    'dibayar', 
    'user_id'
    ];

    public function outlet()   
    { 
        return $this->belongsTo(Outlet::class, 'outlet_id'); 
    }

    public function member()   {
        return $this->belongsTo(Member::class, 'member_id'); 
    }

    public function user()     
    { 
        return $this->belongsTo(User::class, 'user_id'); 
    }
    
    public function details()  
    { 
        return $this->hasMany(DetailTransaksi::class, 'transaksi_id'); 
    }
}
