<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

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
            'product_name' => Str::random(10),
            'description' => Str::random(10),
            'style' => Str::random(10),
            'brand' => Str::random(10),
            'url' => $this->faker->domainName,
            'product_type' => Str::random(10),
            'shipping_price' => $this->faker->numberBetween(100, 5000),
            'note' => '',
            'admin_id' => User::factory()->create()->id,
        ];
    }
}
