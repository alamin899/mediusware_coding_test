<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\ProductVariantPrice;
use App\Models\Variant;
use App\Repositories\ProductRepository;
use App\Repositories\ProductVariantRepository;
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
    public function index(Request $request,ProductVariantRepository $productVariantRepository)
    {
        $productVariants = $productVariantRepository->getProductVariantGroupWise();
        $products = $this->productRepository->getProductByRequest($request->title,$request->variant,$request->price_from,$request->price_to,$request->date)
            ->with($this->reletionShipForIndex())
            ->paginate(getPagination());
        return view('products.index',compact('products','request','productVariants'));
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
    public function store(Request $request)
    {

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
