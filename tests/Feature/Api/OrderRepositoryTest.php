<?php

namespace Tests\Feature\Api;

use App\Models\Inventory;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use App\Repositories\OrdersRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OrderRepositoryTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_total_sales_for_user()
    {
        /** @var User $user */
        $user = User::factory()->create();
        $product = Product::factory()->create(['admin_id' => $user->id]);

        // this is to make sure we only see sales for the specified user
        $user2 = User::factory()->create();
        $product2 = Product::factory()->create(['admin_id' => $user2->id]);

        Order::factory()->create(['total_cents' => 100, 'product_id' => $product->id]);
        Order::factory()->create(['total_cents' => 200, 'product_id' => $product->id]);
        Order::factory()->create(['total_cents' => 500, 'product_id' => $product->id]);

        Order::factory()->create(['total_cents' => 1000, 'product_id' => $product2->id]);

        $repo = new OrdersRepository();

        $totalSales = $repo->totalSalesForUser($user);

        $this->assertEquals(800, $totalSales);
    }

    public function test_average_sale_for_user()
    {
        /** @var User $user */
        $user = User::factory()->create();
        $product = Product::factory()->create(['admin_id' => $user->id]);

        Order::factory()->create(['total_cents' => 200, 'product_id' => $product->id]);
        Order::factory()->create(['total_cents' => 200, 'product_id' => $product->id]);
        Order::factory()->create(['total_cents' => 500, 'product_id' => $product->id]);

        $repo = new OrdersRepository();

        $totalSales = $repo->averageSaleForUser($user);

        $this->assertEquals(300, $totalSales);
    }

        public function test_filtered_total_sales_for_user()
    {
        /** @var User $user */
        $user = User::factory()->create();
        $product1 = Product::factory()->create(['admin_id' => $user->id]);
        $product2 = Product::factory()->create(['admin_id' => $user->id]);
        $inventory1 = Inventory::factory()->create(['product_id' => $product1->id, 'sku' => 'ABC']);
        $inventory2 = Inventory::factory()->create(['product_id' => $product1->id, 'sku' => 'DEF']);

        Order::factory()->create(['total_cents' => 100, 'product_id' => $product1->id, 'inventory_id' => $inventory1]);
        Order::factory()->create(['total_cents' => 200, 'product_id' => $product1->id, 'inventory_id' => $inventory1]);
        Order::factory()->create(['total_cents' => 600, 'product_id' => $product1->id, 'inventory_id' => $inventory2]);
        Order::factory()->create(['total_cents' => 500, 'product_id' => $product2->id]);
        Order::factory()->create(['total_cents' => 500, 'product_id' => $product2->id]);

        $repo = new OrdersRepository();

        $filters = ['product_id' => $product1->id];
        $totalSales = $repo->filteredTotalSalesForUser($user, $filters);

        $this->assertEquals(900, $totalSales);

        $filters = ['sku' => 'ABC'];
        $totalSales = $repo->filteredTotalSalesForUser($user, $filters);

        $this->assertEquals(300, $totalSales);
    }

    public function test_filtered_average_sale_for_user()
    {
        /** @var User $user */
        $user = User::factory()->create();
        $product1 = Product::factory()->create(['admin_id' => $user->id]);
        $product2 = Product::factory()->create(['admin_id' => $user->id]);
        $inventory1 = Inventory::factory()->create(['product_id' => $product1->id, 'sku' => 'ABC']);
        $inventory2 = Inventory::factory()->create(['product_id' => $product1->id, 'sku' => 'DEF']);

        Order::factory()->create(['total_cents' => 100, 'product_id' => $product1->id, 'inventory_id' => $inventory1]);
        Order::factory()->create(['total_cents' => 200, 'product_id' => $product1->id, 'inventory_id' => $inventory1]);
        Order::factory()->create(['total_cents' => 600, 'product_id' => $product1->id, 'inventory_id' => $inventory2]);
        Order::factory()->create(['total_cents' => 500, 'product_id' => $product2->id]);
        Order::factory()->create(['total_cents' => 500, 'product_id' => $product2->id]);

        $repo = new OrdersRepository();

        $filters = ['product_id' => $product1->id];
        $averageSale = $repo->filteredAverageSaleForUser($user, $filters);

        $this->assertEquals(300, $averageSale);

        $filters = ['sku' => 'ABC'];
        $averageSale = $repo->filteredAverageSaleForUser($user, $filters);

        $this->assertEquals(150, $averageSale);
    }

    public function test_order_breakdown_by_status_and_state()
    {
        /** @var User $user */
        $user = User::factory()->create();

        $product = Product::factory()->create(['admin_id' => $user->id]);

        $shipped = 'Shipped';
        $pending = 'Pending';
        $paid = 'Paid';
        $open = 'Open';
        $fulfilled = 'Fulfulled';

        Order::factory(7)->create(['product_id' => $product->id, 'state' => 'FL', 'order_status' => $open]);
        Order::factory(3)->create(['product_id' => $product->id, 'state' => 'FL', 'order_status' => $pending]);
        Order::factory(2)->create(['product_id' => $product->id, 'state' => 'FL', 'order_status' => $shipped]);
        Order::factory(9)->create(['product_id' => $product->id, 'state' => 'FL', 'order_status' => $paid]);
        Order::factory(10)->create(['product_id' => $product->id, 'state' => 'FL', 'order_status' => $fulfilled]);

        Order::factory(3)->create(['product_id' => $product->id, 'state' => 'CA', 'order_status' => $open]);
        Order::factory(7)->create(['product_id' => $product->id, 'state' => 'CA', 'order_status' => $pending]);
        Order::factory()->create(['product_id' => $product->id, 'state' => 'CA', 'order_status' => $shipped]);

        Order::factory(4)->create(['product_id' => $product->id, 'state' => 'NY', 'order_status' => $shipped]);
        Order::factory()->create(['product_id' => $product->id, 'state' => 'NY', 'order_status' => $paid]);

        $repo = new OrdersRepository();

        $breakdown = $repo->orderBreakdownForUser($user);

        $this->assertEquals([
            [
                'state' => 'CA',
                'open' => '3',
                'pending' => '7',
                'shipped' => '1',
                'paid' => '0',
                'fulfilled' => '0',
            ],
            [
                'state' => 'FL',
                'open' => '7',
                'pending' => '3',
                'shipped' => '2',
                'paid' => '9',
                'fulfilled' => '10',
            ],
            [
                'state' => 'NY',
                'open' => '0',
                'pending' => '0',
                'shipped' => '4',
                'paid' => '1',
                'fulfilled' => '0',
            ]
        ], $breakdown);
    }
}
