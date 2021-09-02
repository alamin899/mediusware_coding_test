<?php

namespace App\Repositories;

use App\Models\ProductVariantPrice;

class ProductVariantPriceRepository
{
    public function store($request)
    {
        return ProductVariantPrice::create([
            'product_variant_one' => $request->product_variant_one,
            'product_variant_two' => $request->product_variant_two,
            'product_variant_three' => $request->product_variant_three,
            'price' => $request->price,
            'stock' => $request->stock,
            'product_id' => $request->product_id,
        ]);
    }

}