<?php

namespace App\Contracts;


interface ModuleDecoratorInterface
{

    public function decorate(): callable ;
}