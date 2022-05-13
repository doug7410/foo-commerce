<?php

namespace App\Repositories;

use App\Contracts\RepositoryInterface;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;


class ProductRepository
{

    public function listForUser(User $user, $filters = []): Collection
    {
        $query = $user->products()
            ->with('inventory', function ($query) {
                return $query->select(['sku', 'product_id']);
            })
            ->leftJoin('inventory', 'inventory.product_id', '=', 'products.id')
            ->selectRaw('
                products.id, 
                product_name, 
                description, 
                style, 
                brand, 
                product_type, 
                shipping_price, 
                products.note,
                SUM(inventory.quantity * inventory.price_cents) as potential_revenue
            ')
            ->groupBy('products.id');

        if (isset($filters['sort']) && $filters['sort']) {
            // if it is set and it's not null
            if (isset($filters['sort']['potential_revenue']) && $filters['sort']['potential_revenue']) {
                $query = $query->orderBy('potential_revenue', $filters['sort']['potential_revenue']);
            }
        } else {
            $query = $query->orderBy('created_at', 'desc');
        }

        return $query->get();
    }

    public function createForUser(User $user, array $record): void
    {
        $product = new Product($record);
        $user->products()->save($product);
    }

    public function update(Product $product, array $data): void
    {
        $product->update($data);
    }

    public function delete(Product $product): void
    {
        $product->delete();
    }

    public function listForUserWithDeleted(User $user): Collection
    {
        return $user->products()->withTrashed()->get();
    }
}