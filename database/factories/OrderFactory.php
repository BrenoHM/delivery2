<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Order>
 */
class OrderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'tenant_id' => 1,
            'name' => 'Order Test',
            'phone' => '(31) 3333-3333',
            'payment_method' => 'card',
            'delivery_method' => 'local',
            'total' => 10,
            'status_order_id' => 4
        ];
    }
}
