<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PreviousPassword extends Model
{
    use HasFactory;
    protected $table = "previous_password";
    protected $fillable = [
        "user_id",
        "last_password"
    ];
}
