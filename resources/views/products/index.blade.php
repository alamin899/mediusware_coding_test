@extends('layouts.app')

@section('content')

    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Products</h1>
    </div>


    <div class="card">
        <form action="{{route('product.index')}}" method="get" class="card-header">
            <div class="form-row justify-content-between">
                <div class="col-md-2">
                    <input type="text" name="title" @if(isset($request->title)) value={{$request->title}} @endif placeholder="Product Title" class="form-control">
                </div>
                <div class="col-md-2">
                    <select name="variant" id="" class="form-control">
                        <option>Select</option>
                        @foreach($productVariants as $index => $productVariant)
                            <optgroup label="{{$index}}">
                                @foreach($productVariant as $index => $variant)
                                    <option value="{{$variant->first()->variant->id}}">{{$index}}</option>
                                @endforeach
                            </optgroup>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-3">
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text">Price Range</span>
                        </div>
                        <input type="number" name="price_from"  @if(isset($request->price_from)) value={{$request->price_from}} @endif aria-label="First name"  placeholder="From"
                               class="form-control">
                        <input type="number" name="price_to" @if(isset($request->price_to)) value={{$request->price_to}} @endif aria-label="Last name" placeholder="To" class="form-control">
                    </div>
                </div>
                <div class="col-md-2">
                    <input type="date" name="date" placeholder="Date"  @if(isset($request->date)) value={{$request->date}} @endif class="form-control">
                </div>
                <div class="col-md-1">
                    <button type="submit" name="search" value="search" class="btn btn-primary float-right"><i class="fa fa-search"></i></button>
                </div>
            </div>
        </form>

        <div class="card-body">
            <div class="table-response">
                <table class="table">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>Title</th>
                        <th>Description</th>
                        <th>Variant</th>
                        <th width="70px">Action</th>
                    </tr>
                    </thead>
                    <tbody>

                    @forelse($products as $index => $product)
                        <tr>
                            <td>{{$products->firstItem()+$index}}</td>
                            <td>{{$product->title}} <br> Created at : {{$product->created_at->diffForHumans()}}</td>
                            <td>{{$product->description}}</td>
                            <td>
                                @if(isset($product->productVariantPrices))
                                    <dl class="row mb-0" style="height: 80px; overflow: hidden" id="variant">
                                        @foreach($product->productVariantPrices as $productVariantPrice)
                                            <dt class="col-sm-4 pb-0">
                                                @if(isset($productVariantPrice->productVariantTwo))
                                                    {{$productVariantPrice->productVariantTwo->variant_name}}
                                                @endif
                                                @if(isset($productVariantPrice->productVariantOne))
                                                    /{{$productVariantPrice->productVariantOne->variant_name}}
                                                @endif
                                                @if(isset($productVariantPrice->productVariantThree))
                                                    /{{$productVariantPrice->productVariantThree->variant_name}}
                                                @endif
                                            </dt>
                                            <dd class="col-sm-8">
                                                <dl class="row mb-0">
                                                    <dt class="col-sm-6 pb-0">Price
                                                        : {{ number_format($productVariantPrice->price,2) }}</dt>
                                                    <dd class="col-sm-6 pb-0">InStock
                                                        : {{ number_format($productVariantPrice->stock,2) }}</dd>
                                                </dl>
                                            </dd>
                                        @endforeach
                                    </dl>
                                @endif
                                <button onclick="$('#variant').toggleClass('h-auto')" class="btn btn-sm btn-link">Show
                                    more
                                </button>
                            </td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <a href="{{ route('product.edit', 1) }}" class="btn btn-success">Edit</a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td>No Data Fount</td>
                        </tr>
                    @endforelse

                    </tbody>

                </table>
            </div>

        </div>

        <div class="card-footer">
            <div class="row justify-content-between">
                <div class="col-md-6">
                    <p>Showing {{$products->firstItem()}} to {{$products->lastItem()}} out of {{$products->total()}}</p>
                </div>
                <div class="col-md-4">
                    {{$products->appends(request()->except('page'))->links()}}
                </div>
            </div>
        </div>
    </div>

@endsection
