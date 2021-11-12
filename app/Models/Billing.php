<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Billing extends Model
{
    use HasFactory;

    protected $table = "billing";
    protected $primaryKey = "kode_billing";
    public $incrementing = false;
    protected $fillable = [
        "kode_billing",
        "user_id",
        "total_biaya",
        "status",
        "tanggal_bayar",
        "metode_pembayaran",
        "nominal_pembayaran",
        "nominal_pembayaran",
        "nominal_kembalian"
    ];

    public function user(){
        return $this->belongsTo("App\Models\User", "user_id", "user_id");
    }
}
