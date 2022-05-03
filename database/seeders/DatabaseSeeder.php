<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        (new UserSeeder)->run();
        (new ProductsSeeder())->run();
        (new InventorySeeder())->run();
        (new OrdersSeeder())->run();
    }
}
