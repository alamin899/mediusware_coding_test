<?php

namespace App\Repositories;

use App\Models\Product;

class ProductRepository
{
    public function getProductByRequest($product_title = '')
    {
        $products = Product::query();
        $products->when((!empty($product_title)), function ($products) use ($product_title) {
            $products->where('title','LIKE', '%'. $product_title .'%');
        });
        return $products;
    }
}