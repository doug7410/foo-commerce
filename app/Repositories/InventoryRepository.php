<?php

namespace App\Repositories;


use App\Models\User;

class InventoryRepository
{

    public function listForUser(User $user, $paginate = 20, array $filters = [])
    {
        return $user->inventory()
            ->where($filters)
            ->with('product')
            ->paginate($paginate);
    }
}