<?php

namespace Database\Seeders;

use App\Models\PlanItem;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PlanItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        PlanItem::insert([
            [  
                'name' => '1.000 Pedidos por mês',
                'plan_id' => 1,
                'amount' => 1,
                'value' => 3000,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [  
                'name' => '5.000 Pedidos por mês',
                'plan_id' => 2,
                'amount' => 1,
                'value' => 5000,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [  
                'name' => 'Pedidos Ilimitados',
                'plan_id' => 3,
                'amount' => 1,
                'value' => 10000,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ]
        ]);
    }
}
