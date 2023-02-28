<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductVariationOption extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'product_id',
        'variation_option_id',
        'price'
    ];

    protected $visible = [
        'id',
        'product_id',
        'variation_option_id',
        'price',
        'option'
    ];

    public function option(){
        return $this->belongsTo(VariationOption::class, 'variation_option_id');
    }

    public function product(){
        return $this->belongsTo(Product::class);
    }
}
