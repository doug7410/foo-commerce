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

    public function orderBreakdownForUser(User $user) : array
    {
        $orders =  $user->orders()->select(['state', 'order_status', 'product_id'])->get();

        $result = [];

        foreach ($orders as $order) {
            if(isset($result[$order->state])) {
                if(isset($result[$order->state][$order->order_status])) {
                    ++$result[$order->state][$order->order_status];
                } else {
                    $result[$order->state][$order->order_status] = 1;
                }
            } else {
                $result[$order->state] = [$order->order_status => 1];
            }
        }

        return $result;
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