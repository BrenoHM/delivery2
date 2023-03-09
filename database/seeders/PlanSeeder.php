<?php

namespace Database\Seeders;

use App\Models\Plan;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PlanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Plan::insert([
            [  
                'name' => 'Plano Básico',
                'plan_id' => 10213,
                'interval' => 1,
                'repeats' => null,
                'trial_days' => 30,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [  
                'name' => 'Plano Médio',
                'plan_id' => 10214,
                'interval' => 1,
                'repeats' => null,
                'trial_days' => null,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [  
                'name' => 'Plano Avançado',
                'plan_id' => 10215,
                'interval' => 1,
                'repeats' => null,
                'trial_days' => null,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ]
        ]);
    }
}
