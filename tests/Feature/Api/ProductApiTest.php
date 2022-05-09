<?php

namespace Tests\Api\Feature;

use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductApiTest extends TestCase
{

    use RefreshDatabase;

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

    public function test_can_edit_a_product()
    {
        /** @var User $user */
        $user = User::factory()->create();
        $this->actingAs($user);
        $product = Product::factory()->create([
            'product_name' => 'Awesome Shirt',
            'description' => 'Wackadoo!!',
            'style' => 'fun',
            'brand' => 'Foo',
            'product_type' => 'clothing',
            'shipping_price' => 1234,
            'note' => 'foo note',
            'admin_id' => $user->id,
        ]);

        $response = $this->postJson("/api/products/{$product->id}", [
            'product_name' => 'Awesome Shirt',
            'description' => 'Cheese and Crackers',
            'style' => 'fun',
            'brand' => 'Foo',
            'product_type' => 'clothing',
            'shipping_price' => 1234,
            'note' => 'bar note',
        ]);

        $response->assertStatus(200);
        $this->assertEquals('Cheese and Crackers', $product->fresh()->description);
        $this->assertEquals('bar note', $product->fresh()->note);
    }

    public function test_can_not_update_product_if_it_does_not_belong_to_user()
    {
        /** @var User $user */
        $user = User::factory()->create();
        $this->actingAs($user);
        $product = Product::factory()->create();
        $response = $this->postJson("/api/products/{$product->id}", [
            'note' => 'bar note',
        ]);

        $response->assertStatus(403);
    }

    public function test_can_delete_a_product()
    {
        $user = User::factory()->create();
        $this->actingAs($user);
        $product = Product::factory()->create(['admin_id' => $user->id]);

        $response = $this->delete("/api/products/{$product->id}");

        $response->assertStatus(200);

        $this->assertDatabaseMissing('products', ['id' => $product-> id, 'deleted_at' => null]);
    }

    public function test_can_not_delete_a_product_that_does_not_belong_to_the_authorized_user()
    {
        $user = User::factory()->create();
        $this->actingAs($user);
        $product = Product::factory()->create();

        $response = $this->delete("/api/products/{$product->id}");

        $response->assertStatus(403);

        $this->assertDatabaseHas('products', ['id' => $product-> id, 'deleted_at' => null]);
    }

    public function test_can_get_product_list_with_soft_deleted()
    {
        $user = User::factory()->create();
        $this->actingAs($user);
        $product = Product::factory()->create(['admin_id' => $user->id]);
        Product::factory()->create(['admin_id' => $user->id]);
        $this->delete("/api/products/{$product->id}");

        $response = $this->getJson('/api/products?with_deleted=true');

        $this->assertCount(2, $response->json()['products']);

    }
}
