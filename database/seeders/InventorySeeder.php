<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class InventorySeeder extends CsvSeeder
{

    public function run()
    {
        $this->insertData(__DIR__ . '/../data/inventory.csv', 'inventory', function ($item) {
            return [
                'id' => $item['id'],
                'product_id' => $item['product_id'],
                'quantity' => $item['quantity'],
                'color' => $item['color'],
                'size' => $item['size'],
                'weight' => $item['weight'],
                'price_cents' => $item['price_cents'],
                'sale_price_cents' => $item['sale_price_cents'],
                'cost_cents' => $item['cost_cents'],
                'sku' => $item['sku'],
                'length' => $item['length'],
                'width' => $item['width'],
                'height' => $item['height'],
                'note' => $item['note'],
            ];
        });
    }
}
