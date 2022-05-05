<?php

namespace App\Repositories;


use App\Contracts\RepositoryInterface;
use App\Models\User;

class InventoryRepository implements RepositoryInterface
{

    public function listForUser(User $user, $paginate = 20, array $filters = [])
    {
        return $user->inventory()
            ->where($filters)
            ->with('product')
            ->paginate($paginate);
    }


    public function createForUser(User $user, array $record)
    {
        // TODO: Implement createForUser() method.
    }
}