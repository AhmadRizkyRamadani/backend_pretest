<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailBilling extends Model
{
    use HasFactory;
    protected $table = "detail_billing";
    public $incrementing = false;
    protected $fillable = [
        "kode_billing",
        "kode_transaksi",
    ];
}
