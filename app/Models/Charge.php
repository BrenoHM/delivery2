<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Charge extends Model
{
    use HasFactory;

    protected $fillable = [
        'charge_id',
        'custom_id',
        'subscription_id',
        'parcel',
        'status',
        'total'
    ];

}
