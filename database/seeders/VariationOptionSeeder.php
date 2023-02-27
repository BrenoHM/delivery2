<?php

namespace Database\Seeders;

use App\Models\VariationOption;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class VariationOptionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        VariationOption::insert([
            [
                'variation_id' => 1,
                'option' => 'Pequeno',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'variation_id' => 1,
                'option' => 'Médio',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'variation_id' => 1,
                'option' => 'Grande',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'variation_id' => 2,
                'option' => '200ml',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'variation_id' => 2,
                'option' => '350ml',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'variation_id' => 2,
                'option' => '400ml',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'variation_id' => 2,
                'option' => '500ml',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'variation_id' => 2,
                'option' => '700ml',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'variation_id' => 2,
                'option' => '1L',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'variation_id' => 2,
                'option' => '1.5L',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'variation_id' => 2,
                'option' => '2L',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'variation_id' => 2,
                'option' => '2.5L',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'variation_id' => 2,
                'option' => '3L',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
        ]);
    }
}
