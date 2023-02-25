<?php

namespace Database\Seeders;

use App\Models\Freight;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class FreightSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Freight::insert([
            [
                'tenant_id' => 1,
                'neighborhood' => 'Mantiqueira',
                'city' => 'Belo Horizonte',
                'state' => 'MG',
                'price' => 10,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ]
        ]);
    }
}
