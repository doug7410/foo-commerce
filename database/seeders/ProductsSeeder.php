<?php

namespace Database\Seeders;


class ProductsSeeder extends CsvSeeder
{

    public function run()
    {
        $this->insertData(__DIR__ . '/../data/products.csv', 'products', function ($product) {
            return [
                'id' => $product['id'],
                'product_name' => $product['product_name'],
                'description' => $product['description'],
                'style' => $product['style'],
                'brand' => $product['brand'],
                'url' => $product['url'],
                'product_type' => $product['product_type'],
                'shipping_price' => $product['shipping_price'],
                'note' => $product['note'],
                'admin_id' => $product['admin_id'],
                'created_at' => $product['created_at'],
                'updated_at' => $product['updated_at'],
            ];
        });
    }
}
