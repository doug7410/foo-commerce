<?php

namespace Database\Seeders;


class UserSeeder extends CsvSeeder
{
    public function run()
    {
        $this->insertData(__DIR__ . '/../data/users.csv', 'users', function ($user) {
            return [
                'id' => $user['id'],
                'name' => $user['name'],
                'email' => $user['email'],
                'password' => bcrypt($user['password_plain']),
                'superadmin' => $user['superadmin'],
                'shop_name' => $user['shop_name'],
                'card_brand' => $user['card_brand'],
                'card_last_four' => $user['card_last_four'],
                'trial_starts_at' => $user['trial_starts_at'],
                'trial_ends_at' => $user['trial_ends_at'],
                'shop_domain' => $user['shop_domain'],
                'is_enabled' => $user['is_enabled'],
                'billing_plan' => $user['billing_plan'],
                'created_at' => $user['created_at'],
                'updated_at' => $user['updated_at'],
            ];
        });
    }
}
