<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class VariationOption extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'variation_id',
        'option'
    ];

    // public function opa(){
    //     return $this->hasMany(ProductVariationOption::class);
    // }
}
