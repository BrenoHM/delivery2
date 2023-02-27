<?php

namespace Database\Seeders;

use App\Models\Variation;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class VariationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Variation::insert([
            [
                'variation' => 'Tamanhos',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'variation' => 'Litros',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
        ]);
    }
}
