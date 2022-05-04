<?php

namespace App\Repositories;



use App\Contracts\ModuleRepositoryInterface;
use App\Models\Product;
use Illuminate\Database\Eloquent\Collection;


class ProductRepository implements ModuleRepositoryInterface
{

    public function listForUser(int $userId): Collection
    {
        return Product::where('admin_id', $userId)->get();
    }
}