<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Repositories\ProductRepository;

class ProductsController extends Controller
{
    public function index(ProductRepository $repository)
    {
        $items = $repository->listForUser(auth()->id());

        return response()->json([
            'products' => $items,
        ]);
    }
}
