<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Tenant extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'logo',
        'domain',
        'primaryColor',
        'secondaryColor',
        'zip_code',
        'street',
        'number',
        'neighborhood',
        'state',
        'city'
    ];
}