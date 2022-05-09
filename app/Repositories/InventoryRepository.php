<?php

namespace App\Repositories;


use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class InventoryRepository
{

    public function listForUser(User $user, $paginate = 20, array $filters = []): LengthAwarePaginator
    {
        return $user->inventory()->with(['product' => function($query){
            //TODO: withTrashed is not working. find a workaround
            return $query->withTrashed();
        }])
            ->where($filters)
            ->paginate($paginate);
    }
}