<?php

namespace App\Repositories;


use App\Contracts\RepositoryInterface;
use App\Models\User;

class OrdersRepository implements RepositoryInterface
{

    public function listForUser(User $user, $paginate = 20, array $filters = [])
    {
        return $user->orders()
            ->with('product')
            ->with('inventory')
            ->paginate($paginate);
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