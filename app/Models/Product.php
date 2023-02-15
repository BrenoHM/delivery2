<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'description',
        'tenant_id',
        'category_id',
        'photo',
        'price'
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function additions(){
        return $this->belongsToMany(Addition::class);
    }
}
