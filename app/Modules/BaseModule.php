<?php

namespace App\Modules;


use App\Contracts\ModuleDecoratorInterface;
use App\Contracts\ModuleInterface;

abstract class BaseModule implements ModuleInterface, ModuleDecoratorInterface
{

}