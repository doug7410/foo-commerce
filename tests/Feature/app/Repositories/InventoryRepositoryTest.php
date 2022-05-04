<?php


namespace Tests\Feature\app\Repositories;

use App\Models\Inventory;
use App\Models\Product;
use App\Models\User;
use App\Repositories\InventoryRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class InventoryRepositoryTest extends TestCase
{

    use RefreshDatabase;

    public function test_get_paginated_inventory_for_a_user()
    {
        /** @var User $user */
        $user = User::factory()->create();

        $product1 = Product::factory()->create(['admin_id' => $user->id, 'product_name' => 'Awesome Tee Shirt']);
        $product2 = Product::factory()->create(['admin_id' => $user->id, 'product_name' => 'Awesome Hat']);

        $inventory1 = Inventory::factory()->create(['product_id' => $product1->id]);
        Inventory::factory()->create(['product_id' => $product1->id]);
        Inventory::factory()->create(['product_id' => $product2->id]);
        Inventory::factory()->create(['product_id' => $product2->id]);

        $repo = new InventoryRepository();
        $paginate = 2;
        $list = $repo->listForUser($user, $paginate);

        $this->assertEquals('Illuminate\Pagination\LengthAwarePaginator', get_class($list));
        $this->assertCount(2, $list);
        $this->assertEquals(4, $list->total());

        $this->assertEquals('Awesome Tee Shirt', collect($list->items())->where('id', $inventory1->id)->first()->product->product_name);
    }

    public function test_filter_inventory_list()
    {

        /** @var User $user */
        $user = User::factory()->create();

        $product1 = Product::factory()->create(['admin_id' => $user->id, 'product_name' => 'Awesome Tee Shirt']);
        $product2 = Product::factory()->create(['admin_id' => $user->id, 'product_name' => 'Awesome Hat']);

        $inventory1 = Inventory::factory()->create(['product_id' => $product1->id, 'sku' => 'abcd']);
        $inventory2 = Inventory::factory()->create(['product_id' => $product2->id, 'sku' => 'efgh']);
        $inventory3 = Inventory::factory()->create(['product_id' => $product2->id, 'sku' => 'ijkl']);

        $repo = new InventoryRepository();
        $paginate = 5;

        $filters = [
            ['product_id', '=', $product2->id]
        ];
        $list = $repo->listForUser($user, $paginate, $filters);

        $this->assertCount(2, $list);
        $this->assertNotNull(collect($list->items())->where('id', $inventory2->id)->first());
        $this->assertNotNull(collect($list->items())->where('id', $inventory3->id)->first());
        $this->assertNull(collect($list->items())->where('id', $inventory1->id)->first());

        $filters = [
            ['sku', '=', 'efgh']
        ];
        $list = $repo->listForUser($user, $paginate, $filters);

        $this->assertCount(1, $list);
        $this->assertNotNull(collect($list->items())->where('id', $inventory2->id)->first());
        $this->assertNull(collect($list->items())->where('id', $inventory1->id)->first());
        $this->assertNull(collect($list->items())->where('id', $inventory3->id)->first());

    }
}