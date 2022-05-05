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
}
