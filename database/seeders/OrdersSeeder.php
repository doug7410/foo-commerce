<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class OrdersSeeder extends CsvSeeder
{

    public function run()
    {
        $this->insertData(__DIR__ . '/../data/orders.csv', 'orders', function ($record) {
            return [
                'id' => $record['id'],
                'product_id' => $record['product_id'],
                'inventory_id' => $record['inventory_id'],
                'street_address' => $record['street_address'],
                'apartment' => $record['apartment'],
                'city' => $record['city'],
                'state' => $record['state'],
                'country_code' => $record['country_code'],
                'zip' => $record['zip'],
                'phone_number' => $record['phone_number'],
                'email' => $record['email'],
                'name' => $record['name'],
                'order_status' => $record['order_status'],
                'payment_ref' => $record['payment_ref'],
                'transaction_id' => $record['transaction_id'] === 'NULL' ? null : $record['transaction_id'],
                'payment_amt_cents' => $record['payment_amt_cents'],
                'ship_charged_cents' => $record['ship_charged_cents'] === 'NULL' ? null : $record['ship_charged_cents'],
                'ship_cost_cents' => $record['ship_cost_cents'] === 'NULL' ? null : $record['ship_cost_cents'],
                'subtotal_cents' => $record['subtotal_cents'],
                'total_cents' => $record['total_cents'],
                'shipper_name' => $record['shipper_name'],
                'payment_date' => $record['payment_date'],
                'shipped_date' => $record['shipped_date'],
                'tracking_number' => $record['tracking_number'],
                'tax_total_cents' => $record['tax_total_cents'],
                'created_at' => $record['created_at'],
                'updated_at' => $record['updated_at'],
            ];
        });
    }
}
