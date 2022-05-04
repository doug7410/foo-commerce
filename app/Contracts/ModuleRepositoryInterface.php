<?php

namespace App\Contracts;





use Illuminate\Database\Eloquent\Collection;

interface ModuleRepositoryInterface
{
    public function listForUser(int $userId): Collection;
}