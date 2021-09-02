<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\ProductVariantPrice;
use App\Models\Variant;
use App\Repositories\ProductRepository;
use App\Repositories\ProductVariantPriceRepository;
use App\Repositories\ProductVariantRepository;
use http\Env\Response;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * @var ProductRepository
     */
    private $productRepository;

    public function __construct(ProductRepository $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Http\Response|\Illuminate\View\View
     */
    public function index(Request $request, ProductVariantRepository $productVariantRepository)
    {
        $productVariants = $productVariantRepository->getProductVariantGroupWise();
        $products = $this->productRepository->getProductByRequest($request->title, $request->variant, $request->price_from, $request->price_to, $request->date)
            ->with($this->reletionShipForIndex())
            ->paginate(getPagination());
        return view('products.index', compact('products', 'request', 'productVariants'));
    }

    private function reletionShipForIndex()
    {
        return ['productVariantPrices', 'productVariantPrices.productVariantOne:id,variant as variant_name,variant_id',
            'productVariantPrices.productVariantOne.variant:id,title', 'productVariantPrices.productVariantTwo:id,variant as variant_name,variant_id', 'productVariantPrices.productVariantTwo.variant:id,title',
            'productVariantPrices.productVariantThree:id,variant as variant_name,variant_id', 'productVariantPrices.productVariantThree.variant:id,title'];
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Http\Response|\Illuminate\View\View
     */
    public function create()
    {
        $variants = Variant::all();
        return view('products.create', compact('variants'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request, ProductVariantRepository $productVariantRepository,ProductVariantPriceRepository $productVariantPriceRepository)
    {
        $storeProductVariantData = [];
        $productStore = $this->productRepository->store($this->customProductRequest($request));
        foreach ($request->product_variant as $p_variant) {
            foreach ($p_variant['tags'] as  $tag) {
                $storeProductVariant = $productVariantRepository->store($this->customProductVariantRequest($tag, $p_variant['option'], $productStore->id));
                $storeProductVariantData[$p_variant['option']][] = $storeProductVariant['id'];
            }
        }
        $totalCombination = combination($storeProductVariantData);
        foreach ($request->product_variant_prices as $pvpIndex => $product_variant_price){
        $requestData  = $this->customProductVariantPriceRequest($totalCombination[$pvpIndex],$product_variant_price,$productStore->id);
        $store = $productVariantPriceRepository->store($requestData);
        }
        return ($store)?"success" : "failed";
    }

    private function customProductRequest($request)
    {
        return new Request([
            'title' => $request->title,
            'sku' => $request->sku,
            'description' => $request->description,
        ]);
    }

    private function customProductVariantRequest($variant, $variant_id, $product_id)
    {
        return new Request([
            'variant' => $variant,
            'variant_id' => $variant_id,
            'product_id' => $product_id,
        ]);
    }

    private function customProductVariantPriceRequest($productVariants,$product_variant_price,$product_id)
    {
        return new Request([
            'product_variant_one' => (isset($productVariants[0]))?$productVariants[0]:null,
            'product_variant_two' => (isset($productVariants[1]))?$productVariants[1]:null,
            'product_variant_three' => (isset($productVariants[2]))?$productVariants[2]:null,
            'price' => $product_variant_price['price'],
            'stock' => $product_variant_price['stock'],
            'product_id' => $product_id,
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\Product $product
     * @return \Illuminate\Http\Response
     */
    public function show($product)
    {

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Models\Product $product
     * @return \Illuminate\Http\Response
     */
    public function edit(Product $product)
    {
        $variants = Variant::all();
        return view('products.edit', compact('variants'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Product $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Product $product)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Product $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
        //
    }
}
