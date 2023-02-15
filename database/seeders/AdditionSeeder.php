<?php

namespace Database\Seeders;

use App\Models\Addition;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AdditionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Addition::insert([
            [
                'addition' => 'Bacon',
                'tenant_id' => 1,
                'price' => 25.5,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'addition' => 'Alface',
                'tenant_id' => 1,
                'price' => 20,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'addition' => 'Salame',
                'tenant_id' => 1,
                'price' => 50,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'addition' => 'Bacon',
                'tenant_id' => 2,
                'price' => 50,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'addition' => 'Tomate',
                'tenant_id' => 2,
                'price' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ]
        ]);
    }
}
