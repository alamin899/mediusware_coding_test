<?php

namespace App\Repositories;

use App\Models\ProductVariant;

class ProductVariantRepository
{
    public function getProductVariantGroupWise()
    {
        return ProductVariant::with('variant')->get(['id', 'variant as variant_name', 'variant_id'])->groupBy(['variant.title', 'variant_name']);
    }

    public function store($request)
    {
        $pvariant = new ProductVariant();
        $pvariant->variant = $request->variant;
        $pvariant->variant_id = $request->variant_id;
        $pvariant->product_id = $request->product_id;
        $pvariant->save();
        return ['id' => $pvariant->id];
    }

}