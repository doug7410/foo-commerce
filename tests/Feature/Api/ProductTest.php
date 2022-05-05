<?php

namespace Tests\Api\Feature;

use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductTest extends TestCase
{

    use RefreshDatabase;

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_can_create_a_product()
    {
        /** @var User $user */
        $user = User::factory()->create();
        $this->actingAs($user);

        $product = [
            'product_name' => 'Awesome Shirt',
            'description' => 'Wackadoo!!',
            'style' => 'fun',
            'brand' => 'Foo',
            'product_type' => 'clothing',
            'shipping_price' => 1234,
            'note' => 'foo note',
        ];

        $response = $this->postJson('/api/products', $product);

        $response->assertStatus(200);
        $this->assertDatabaseHas(Product::class, $product);
    }

    public function test_can_validate_product_request_data()
    {
        /** @var User $user */
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->postJson('/api/products', []);

        $response->assertStatus(422);

        $response->assertJsonFragment(['product_name' => ['The product name field is required.']]);
        $response->assertJsonFragment(['description' => ['The description field is required.']]);
        $response->assertJsonFragment(['style' => ['The style field is required.']]);
        $response->assertJsonFragment(['brand' => ['The brand field is required.']]);
        $response->assertJsonFragment(['product_type' => ['The product type field is required.']]);
        $response->assertJsonFragment(['shipping_price' => ['The shipping price field is required.']]);
    }
}
