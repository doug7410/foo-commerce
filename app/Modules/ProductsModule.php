<?php

namespace App\Modules;


use App\Contracts\ModuleDecoratorInterface;
use App\Contracts\ModuleInterface;
use App\Contracts\ModuleRepositoryInterface;
use App\Models\Product;
use App\Repositories\ProductRepository;

class ProductsModule extends BaseModule
{

    public function model()
    {
        return new Product();
    }

    public function name()
    {
        return 'Products';
    }

    public function moduleName()
    {
        return 'products';
    }

    public function repository(): ModuleRepositoryInterface
    {
        return new ProductRepository();
    }

    public function columns()
    {
        return  [
            'Name' => 'product_name',
            'Style' => 'style',
            'Brand' => 'brand',
        ];
    }

    public function decorate(): callable
    {
        return function ($product) {
            $product->skus = '123, 456';
            return $product;
        };
    }
}