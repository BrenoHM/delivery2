<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LogStatusOrder extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'status_order_id',
    ];
}
