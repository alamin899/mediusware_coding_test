<?php

namespace App\Repositories;

use App\Models\Product;

class ProductRepository
{
    public function getProductByRequest($product_title = '', $variant = '', $price_from = '', $price_to = '', $date = '')
    {
        $products = Product::query();
        $products->when((!empty($product_title)), function ($products) use ($product_title) {
            $products->where('title', 'LIKE', '%' . $product_title . '%');
        });
        $products->when((!empty($variant)), function ($products) use ($variant) {
            $this->getProductByVariant($products, $variant);
        });
        $products->when((!empty($price_from)), function ($products) use ($price_from) {
            $this->getProductByPriceFrom($products, $price_from);
        });
        $products->when((!empty($price_to)), function ($products) use ($price_to) {
            $this->getProductByPriceTo($products, $price_to);
        });
        $products->when((!empty($date)), function ($products) use ($date) {
            $products->whereDate('created_at', $date);
        });
        return $products;
    }

    private function getProductByVariant($products, $variant)
    {
        if ($variant == VARIANT_COLOR) {
            $products->whereHas('productVariantPrices', function ($productVariantPrices) use ($variant) {
                $productVariantPrices->whereHas('productVariantOne', function ($productVariants) use ($variant) {
                    $productVariants->whereHas('variant', function ($variants) use ($variant) {
                        return $variants->where('id', $variant);
                    });
                });
            });
        } elseif ($variant == VARIANT_SIZE) {
            $products->whereHas('productVariantPrices', function ($productVariantPrices) use ($variant) {
                $productVariantPrices->whereHas('productVariantTwo', function ($productVariants) use ($variant) {
                    $productVariants->whereHas('variant', function ($variants) use ($variant) {
                        return $variants->where('id', $variant);
                    });
                });
            });
        } else {
            $products->whereHas('productVariantPrices', function ($productVariantPrices) use ($variant) {
                $productVariantPrices->whereHas('productVariantThree', function ($productVariants) use ($variant) {
                    $productVariants->whereHas('variant', function ($variants) use ($variant) {
                        return $variants->where('id', $variant);
                    });
                });
            });
        }
    }

    private function getProductByPriceFrom($products, $price_from)
    {
        $products->whereHas('productVariantPrices', function ($productVariantPrices) use ($price_from) {
            return $productVariantPrices->where('price', '>=', $price_from);
        });
    }

    private function getProductByPriceTo($products, $price_to)
    {
        $products->whereHas('productVariantPrices', function ($productVariantPrices) use ($price_to) {
            return $productVariantPrices->where('price', '<=', $price_to);
        });
    }
}