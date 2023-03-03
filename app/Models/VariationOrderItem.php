<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VariationOrderItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_item_id',
        'product_variation_option_id',
        'total'
    ];

    public function product_variation()
    {
        return $this->belongsTo(ProductVariationOption::class, 'product_variation_option_id');
    }
}
