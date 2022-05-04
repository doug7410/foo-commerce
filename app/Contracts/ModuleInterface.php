<?php

namespace App\Contracts;


interface ModuleInterface
{

    public function repository(): ModuleRepositoryInterface;
    public function columns();
}