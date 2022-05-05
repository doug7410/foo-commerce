<?php

namespace Tests\Api\Feature;

use App\Models\Inventory;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class InventoryApiTest extends TestCase
{

    use RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_can_get_filtered_list_of_inventory_for_user()
    {

        /** @var User $user */
        $user = User::factory()->create();
        $this->actingAs($user);

        $product1 = Product::factory()->create(['admin_id' => $user->id, 'product_name' => 'Awesome Tee Shirt']);
        $product2 = Product::factory()->create(['admin_id' => $user->id, 'product_name' => 'Awesome Hat']);
        Inventory::factory()->create(['product_id' => $product1->id, 'sku' => 'abcd']);
        Inventory::factory()->create(['product_id' => $product2->id, 'sku' => 'efgh']);
        Inventory::factory()->create(['product_id' => $product2->id, 'sku' => 'ijkl']);

        $filters = json_encode([
            ['product_id', '=', $product1->id]
        ]);


        $response = $this->getJson('/api/inventory?filters=' . urlencode($filters));

        $response->assertStatus(200);
        $response->assertJsonFragment(['product_name' => 'Awesome Tee Shirt']);
        $response->assertJsonMissing(['product_name' => 'Awesome Hat']);
        $response->assertJsonMissing(['sku' => 'efgh']);
    }

    public function test_can_set_records_per_page()
    {
        $user = User::factory()->create();
        $product1 = Product::factory()->create(['admin_id' => $user->id, 'product_name' => 'Awesome Tee Shirt']);
        $product2 = Product::factory()->create(['admin_id' => $user->id, 'product_name' => 'Awesome Hat']);
        Inventory::factory()->create(['product_id' => $product1->id, 'sku' => 'abcd']);
        Inventory::factory()->create(['product_id' => $product2->id, 'sku' => 'efgh']);
        Inventory::factory()->create(['product_id' => $product2->id, 'sku' => 'ijkl']);

        $this->actingAs($user);

        $response = $this->get('/api/inventory?records_per_page=2');

        $response->assertStatus(200);

        $this->assertCount(2, $response->json()['data']);
    }
}
