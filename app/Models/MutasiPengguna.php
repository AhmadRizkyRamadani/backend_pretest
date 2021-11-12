<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MutasiPengguna extends Model
{
    use HasFactory;
    protected $table = "mutasi_pengguna";
    public $incrementing = false;
    protected $fillable = [
        "kode_mutasi",
        "user_sender",
        "user_receiver",
        "nominal",
        "type",
        "keterangan",
        "in_out",
        "status"
    ];
}
