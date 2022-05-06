<?php

namespace Database\Factories;

use App\Models\Inventory;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

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
            'product_id' => Product::factory()->create()->id,
            'inventory_id' => Inventory::factory()->create()->id,
            'street_address' => $this->faker->address,
            'apartment' => $this->faker->numberBetween(10, 100),
            'city' => $this->faker->city,
            'state' => Str::random(2),
            'country_code' => Str::random(2),
            'zip' => $this->faker->postcode,
            'phone_number' => $this->faker->phoneNumber,
            'email' => $this->faker->email,
            'name' => $this->faker->name,
            'order_status' => Str::random(8),
            'payment_ref' => Str::random(8),
            'transaction_id' => Str::random(8),
            'payment_amt_cents' => $this->faker->numberBetween(100, 1000),
            'ship_charged_cents' => $this->faker->numberBetween(100, 1000),
            'ship_cost_cents' => $this->faker->numberBetween(100, 1000),
            'subtotal_cents' => $this->faker->numberBetween(100, 1000),
            'total_cents' => $this->faker->numberBetween(100, 1000),
            'shipper_name' => $this->faker->name,
            'payment_date' => $this->faker->date,
            'shipped_date' => $this->faker->date,
            'tracking_number' => Str::random(20),
            'tax_total_cents' => $this->faker->numberBetween(100, 1000),
        ];
    }
}
