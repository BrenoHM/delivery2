<?php

namespace Database\Seeders;

use App\Models\StatusOrder;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class StatusOrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        StatusOrder::insert([
            [
                'status' => 'Opened',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'status' => 'Preparation',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'status' => 'Transport',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'status' => 'Finished',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'status' => 'Canceled',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ]
        ]);
    }
}
