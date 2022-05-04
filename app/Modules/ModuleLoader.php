<?php

namespace App\Modules;


class ModuleLoader
{

    public function load(string $module) {
        $modules = [
            'products' => ProductsModule::class
        ];

        return new $modules[$module];
    }
}