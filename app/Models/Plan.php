<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Plan extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'plan_id',
        'interval',
        'repeats',
        'trial_days'
    ];

    public function items(){
        return $this->hasMany(PlanItem::class);
    }
}
