<?php

namespace App\Repositories;


use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class InventoryRepository
{

    public function listForUser(User $user, $paginate = 20, array $filters = []): LengthAwarePaginator
    {
        $query =  $user->inventory()->with(['product' => function($query){
            return $query->select('product_name', 'deleted_at', 'id')->withTrashed();
        }]);

        if($filters) {
            $query->where($filters);
        }

        return $query->select(
                'sku',
                'quantity',
                'color',
                'size',
                'price_cents',
                'cost_cents',
                'product_id'
            )
            ->withTrashedParents()
            ->paginate($paginate);
    }
}