<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LogActivity extends Model
{
    use HasFactory;

    protected $table = "log_activity";
    protected $primaryKey = "id";
    protected $fillable = [
        "id",
        "user_id",
        "token",
        "login_date",
    ];
}
