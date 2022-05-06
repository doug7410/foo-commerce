<?php

namespace App\Repositories;

use App\Contracts\RepositoryInterface;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;


class ProductRepository implements RepositoryInterface
{

    public function listForUser(User $user, $paginate = 20, array $filters = []): Collection
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

    public function createForUser(User $user, array $record)
    {
        $product = new Product($record);
        $user->products()->save($product);
    }

    public function updateForUser(User $user, array $data, int $id)
    {
        $user->products()->where('id', $id)->update($data);
    }

    public function deleteForUser(User $user, int $id) {
        $user->products()->where('id', $id)->delete();
    }

    public function listForUserWithDeleted(User $user)
    {
        return $user->products()->withTrashed()->get();
    }
}