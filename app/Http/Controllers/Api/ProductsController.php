<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Http\Controllers\Controller;
use App\Http\Requests\DeleteProductRequest;
use App\Http\Requests\ProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Models\Product;
use App\Repositories\ProductRepository;
use Illuminate\Http\Request;


class ProductsController extends Controller
{

    /**
     * @var ProductRepository
     */
    private $productRepository;

    public function __construct(ProductRepository $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    public function index(Request $request)
    {
        /** @var User $user */
        $user = auth()->user();

        if ($request->input('with_deleted')) {
            $items = $this->productRepository->listForUserWithDeleted($user);
        } else {
            $items = $this->productRepository->listForUser($user);
        }

        return response()->json(['products' => $items]);
    }

    public function store(ProductRequest $request)
    {
        $this->productRepository->createForUser(auth()->user(), $request->validated());
        return response()->json(['message' => 'Product Created']);
    }

    public function update(Product $product, UpdateProductRequest $request)
    {
        $this->productRepository->update($product, $request->validated());
        return response()->json(['message' => 'Product Updated']);
    }

    public function delete(Product $product, DeleteProductRequest $request)
    {
        $this->productRepository->delete($product);
        return response()->json(['message' => 'Product Deleted']);
    }
}
