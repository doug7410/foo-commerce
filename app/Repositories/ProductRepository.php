<?php

namespace App\Repositories;

use App\Contracts\RepositoryInterface;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;


class ProductRepository
{

    public function listForUser(User $user): Collection
    {
        return $user->products()
            ->with('inventory', function ($query) {
                return $query->select(['sku', 'product_id']);
            })
            ->select(
                'id',
                'product_name',
                'description',
                'style',
                'brand',
                'product_type',
                'shipping_price',
                'note'
            )
            ->orderBy('created_at', 'desc')
            ->get();
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