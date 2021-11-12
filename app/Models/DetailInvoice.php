<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailInvoice extends Model
{
    use HasFactory;

    protected $table = "detail_invoice";
    public $incrementing = false;
    protected $fillable = [
        "kode_transaksi",
        "kode_produk",
        "harga",
        "jumlah",
        "total"
    ];

    public function produk(){
        return $this->belongsTo("App\Models\Product", "kode_produk", "kode_produk");
    }
}
