<?php

namespace App\Contracts;


use App\Models\User;


interface RepositoryInterface
{
    public function listForUser(User $User, $paginate = 20, array $filters = []);
    public function createForUser(User $user, array $record);
    public function updateForUser(User $user, array $data, int $id);
    public function deleteForUser(User $user, int $id);

}