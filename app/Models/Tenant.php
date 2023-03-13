<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Tenant extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'logo',
        'domain',
        'primaryColor',
        'secondaryColor',
        'zip_code',
        'street',
        'number',
        'neighborhood',
        'state',
        'city',
        'type_pix_key',
        'pix_key',
        'deleted_at'
    ];

    public function user()
    {
        return $this->hasOne(User::class);
    }

    public function timelines()
    {
        return $this->hasMany(Timeline::class);
    }
}
