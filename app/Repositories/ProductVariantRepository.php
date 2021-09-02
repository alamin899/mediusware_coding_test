<?php

namespace App\Repositories;

use App\Models\ProductVariant;

class ProductVariantRepository
{
    public function getProductVariantGroupWise()
    {
        return ProductVariant::with('variant')->get(['id','variant as variant_name','variant_id'])->groupBy(['variant.title','variant_name']);

    }

}