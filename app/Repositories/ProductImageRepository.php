<?php

namespace App\Repositories;

use App\Models\ProductImage;

class ProductImageRepository
{
    public function store($request)
    {
        return ProductImage::create([
            'product_id' => $request->product_id,
            'file_path' => $request->file_path,
            'thumbnail' => $request->thumbnail,
        ]);
    }
}