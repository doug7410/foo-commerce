<?php

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Inventory>
 */
class InventoryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'product_id' => Product::factory()->create()->id,
            'quantity' =>  $this->faker->numberBetween(1, 20),
            'color' => $this->faker->colorName,
            'size' => Str::random(1),
            'weight' => $this->faker->numberBetween(1, 10),
            'price_cents' => $this->faker->numberBetween(100, 5000),
            'sale_price_cents' => $this->faker->numberBetween(100, 5000),
            'cost_cents' => $this->faker->numberBetween(100, 5000),
            'sku' => Str::random(4),
            'length' => $this->faker->numberBetween(1, 5),
            'width' => $this->faker->numberBetween(1, 5),
            'height' => $this->faker->numberBetween(1, 5),
            'note' => '',
        ];
    }
}
