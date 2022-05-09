<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\User;
use App\Repositories\ProductRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductRepositoryTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_can_create_a_product_for_a_user()
    {
        $product = [
            'product_name' => 'Awesome Shirt',
            'description' => 'Wackadoo!!',
            'style' => 'fun',
            'brand' => 'Foo',
            'product_type' => 'clothing',
            'shipping_price' => 1234,
            'note' => 'foo note',
        ];

        $user = User::factory()->create();
        $repo = new ProductRepository();

        $repo->createForUser($user, $product);

        $product['admin_id'] = $user->id;
        $this->assertDatabaseHas(Product::class, $product);
    }

    public function test_can_update_a_product_for_a_user()
    {
        /** @var User $user */
        $user = User::factory()->create();
        $this->actingAs($user);

        /** @var Product $product */
        $product = Product::factory()->create([
            'description' => 'Wackadoo!!',
            'note' => 'foo note',
            'admin_id' => $user->id,
        ]);

        $repo = new ProductRepository();

        $updtedProduct = [
            'description' => 'Cheese and Crackers',
            'note' => 'bar note',
        ];

        $repo->update($product, $updtedProduct);

        $this->assertEquals('Cheese and Crackers', $product->fresh()->description);
        $this->assertEquals('bar note', $product->fresh()->note);
    }
}
