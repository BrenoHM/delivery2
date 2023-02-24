<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'tenant_id',
        'name',
        'phone',
        'payment_method',
        'delivery_method',
        'zip_code',
        'street',
        'number',
        'complement',
        'neighborhood',
        'city',
        'state',
        'additional_information',
        'freight_total',
        'total',
        'status_order_id'
    ];

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }
}