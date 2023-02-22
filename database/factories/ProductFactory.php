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
            'tenant_id' => 1,
            'category_id' => 3,
            'photo' => env('AWS_URL').'/aAiWU474ziQENMkIgzMt81Oe51xQyuQiPhVule5e.jpg',
            'price' => 29.99,
        ];
        
    }
}
