<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProductRequest;
use App\Repositories\ProductRepository;


class ProductsController extends Controller
{
    public function index(ProductRepository $repository)
    {
        $items = $repository->listForUser(auth()->user());

        return response()->json([
            'products' => $items,
        ]);
    }

    public function store(ProductRepository $repository, ProductRequest $request)
    {
        $repository->createForUser(auth()->user(), $request->all());
        return response()->json(['message' => 'Product Created']);
    }

    public function update(ProductRepository $repository, ProductRequest $request, $id)
    {
        $repository->updateForUser(auth()->user(), $request->validated(), $id);
        return response()->json(['message' => 'Product Updated']);
    }

    public function delete(ProductRepository $repository, $id)
    {
        $repository->deleteForUser(auth()->user(), $id);
        return response()->json(['message' => 'Product Deleted']);
    }
}
