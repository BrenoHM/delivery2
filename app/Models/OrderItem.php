<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'product_id',
        'quantity',
        'total',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function additions()
    {
        return $this->hasMany(AdditionOrderItem::class);
    }

    public function variation()
    {
        return $this->hasOne(VariationOrderItem::class);
    }
}
