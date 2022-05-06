<?php

namespace App\Repositories;


use App\Contracts\RepositoryInterface;
use App\Models\User;

class OrdersRepository implements RepositoryInterface
{

    public function listForUser(User $user, $paginate = 20, array $filters = [])
    {
        $query = $user->orders()->with('product')->with('inventory');

        if (isset($filters['product_id'])) {
            $query->where('product_id', $filters['product_id']);
        }

        if (isset($filters['sku'])) {
            $query->whereHas('inventory', function ($query) use ($filters) {
                return $query->where('sku', $filters['sku']);
            });
        }

        return $query->paginate($paginate);
    }


    public function totalSalesForUser(User $user)
    {
        return $user->orders()->sum('total_cents');
    }

    public function filteredTotalSalesForUser(User $user, array $filters)
    {
        $query = $user->orders();

        if (isset($filters['product_id'])) {
            $query->where('product_id', $filters['product_id']);
        }

        if (isset($filters['sku'])) {
            $query->whereHas('inventory', function ($query) use ($filters) {
                return $query->where('sku', $filters['sku']);
            });
        }

        return $query->sum('total_cents');
    }

    public function averageSaleForUser($user)
    {
        return $user->orders()->average('total_cents');
    }

    public function filteredAverageSaleForUser(User $user, $filters)
    {
        $query = $user->orders();

        if (isset($filters['product_id'])) {
            $query->where('product_id', $filters['product_id']);
        }

        if (isset($filters['sku'])) {
            $query->whereHas('inventory', function ($query) use ($filters) {
                return $query->where('sku', $filters['sku']);
            });
        }

        return $query->average('total_cents');
    }

    public function createForUser(User $user, array $record)
    {
        // TODO: Implement createForUser() method.
    }

    public function updateForUser(User $user, array $data, int $id)
    {
        // TODO: Implement updateForUser() method.
    }

    public function deleteForUser(User $user, int $id)
    {
        // TODO: Implement deleteForUser() method.
    }
}