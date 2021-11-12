<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    protected $table = "product";
    protected $primaryKey = "kode_produk";
    public $incrementing = false;
    protected $fillable = [
        "kode_produk",
        "nama_produk",
        "harga",
        "status"
    ];
}
