<?php

namespace App\Http\Controllers;

use App\Modules\ModuleLoader;

class ModuleController extends Controller
{
    public function index($module)
    {
        $loader = new ModuleLoader();

        $module = $loader->load($module);

        return view('module.index', ['name' => $module->name(), 'moduleName' => $module->moduleName()]);
    }
}
