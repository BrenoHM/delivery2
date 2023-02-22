<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Timeline extends Model
{
    use HasFactory;

    protected $fillable = [
        'day_of_week',
        'tenant_id',
        'start',
        'end',
    ];

    public static function isOpened($tenantId)
    {
        $results = DB::select('SELECT * FROM
                        timelines t 
                        WHERE DAYOFWEEK(NOW()) = t.day_of_week
                        AND t.tenant_id = ?
                        AND CAST(NOW() AS TIME) BETWEEN `start` AND `end`', [$tenantId]);

        return count($results) ? true : false;
    }
}
