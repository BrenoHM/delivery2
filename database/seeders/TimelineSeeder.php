<?php

namespace Database\Seeders;

use App\Models\Timeline;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TimelineSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Timeline::insert([
            [
                'tenant_id' => 1,
                'day_of_week' => 1,
                'start' => "08:00:00",
                'end' => "23:59:59",
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'tenant_id' => 1,
                'day_of_week' => 2,
                'start' => "08:00:00",
                'end' => "23:59:59",
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'tenant_id' => 1,
                'day_of_week' => 3,
                'start' => "08:00:00",
                'end' => "23:59:59",
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'tenant_id' => 1,
                'day_of_week' => 4,
                'start' => "08:00:00",
                'end' => "23:59:59",
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'tenant_id' => 1,
                'day_of_week' => 5,
                'start' => "08:00:00",
                'end' => "23:59:59",
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'tenant_id' => 1,
                'day_of_week' => 6,
                'start' => "08:00:00",
                'end' => "23:59:59",
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'tenant_id' => 1,
                'day_of_week' => 7,
                'start' => "08:00:00",
                'end' => "23:59:59",
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ]
        ]);
    }
}
