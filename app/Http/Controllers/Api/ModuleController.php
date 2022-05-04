<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Modules\BaseModule;
use App\Modules\ModuleLoader;

class ModuleController extends Controller
{
    public function index($module)
    {
        $loader = new ModuleLoader();

        /** @var BaseModule $module */
        $module = $loader->load($module);

        $items = $module->repository()->listForUser(auth()->id());

        return response()->json([
            'items' => $items->map($module->decorate()),
            'columns' => $module->columns()
        ]);
    }
}
