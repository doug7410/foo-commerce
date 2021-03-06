<?php

namespace App\Repositories;


use App\Models\User;

class OrdersRepository
{

    public function listForUser(User $user, $paginate = 20, array $filters = [])
    {
        $query = \DB::table('orders')
            ->join('products', 'products.id', '=', 'orders.product_id')
            ->join('inventory', 'inventory.id', '=', 'orders.inventory_id')
            ->where('products.admin_id', $user->id);

        if (isset($filters['product_id'])) {
            $query->where('orders.product_id', $filters['product_id']);
        }

        if (isset($filters['sku'])) {
            $query->where('inventory.sku', $filters['sku']);
        }

        $query->select(
            'name',
            'email',
            'product_name',
            'sku',
            'color',
            'size',
            'order_status',
            'total_cents',
            'transaction_id',
            'shipper_name',
            'tracking_number',
            'products.deleted_at as product_deleted'
        );

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
        $statuses = ['Open', 'Pending', 'Shipped', 'Paid', 'Fulfulled'];
        $statusesSelect = '';

        foreach ($statuses as $status) {
            $statusesSelect .= ",SUM(if(order_status = '{$status}', 1, 0)) as {$status}";
        }

        $orders = $user->orders()
            ->selectRaw("state, products.admin_id $statusesSelect ")
            ->groupBy('state', 'admin_id')
            ->orderBy('state')
            ->get()
            ->map(function ($state) {
                return [
                    'state' => $state->state,
                    'open' => $state->Open,
                    'pending' => $state->Pending,
                    'shipped' => $state->Shipped,
                    'paid' => $state->Paid,
                    'fulfilled' => $state->Fulfulled,
                ];
            });

        return $orders->toArray();
    }
}