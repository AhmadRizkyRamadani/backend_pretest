<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;
    protected $table = "invoice";
    protected $primaryKey = "kode_transaksi";
    public $incrementing = false;
    protected $fillable = [
        "kode_transaksi",
        "user_id",
        "total_biaya",
        "status",
        "tanggal_bayar",
        "kode_pembayaran",
        "metode_pembayaran",
        "nominal_pembayaran",
        "nominal_kembalian"
    ];

    public function user(){
        return $this->belongsTo("App\Models\User", "user_id", "user_id");
    }

    public function users(){
        return $this->hasMany("App\Models\User", "user_id", "user_id");
    }

    public function detail_invoice(){
        return $this->hasMany("App\Models\DetailInvoice", "kode_transaksi", "kode_transaksi");
    }

    public function detail_billing(){
        return $this->hasMany("App\Models\DetailBilling", "kode_transaksi", "kode_transaksi");
    }
}
