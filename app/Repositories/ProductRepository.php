<?php

namespace App\Repositories;



use App\Contracts\ModuleRepositoryInterface;
use App\Contracts\RepositoryInterface;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;


class ProductRepository implements RepositoryInterface
{

    public function listForUser(User $user, $paginate = 20, array $filters = []): Collection
    {
        return $user->products()->with('inventory')->orderBy('created_at', 'desc')->get();
    }

    public function createForUser(User $user, array $record)
    {
        $product = new Product($record);
        $user->products()->save($product);
    }
}