<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'name' => 'X-Tudo',
            'description' => fake()->text(),
            'user_id' => 2,
            'category_id' => 3,
            'photo' => 'https://files.menudino.com/cardapios/22771/181.jpg',
            'price' => 29.99,
        ];
        
    }
}
