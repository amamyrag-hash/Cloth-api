<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductRequest;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Http\Resources\ProductResource;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        //return response()->json([
          //  'products'=>ProductResource::collection(Product::with('category')->paginate())
        //]);

        $query= Product::with('category');
         if($request->filled('search')){
            $query->where('name' , 'like' , '%' . $request->search .'%');
         }
         if($request->filled('category_id')){
            $query->where('category_id', $request->category_id);
         }
         if($request->filled('max_price')){
            $query->where('price', '<=' , $request->max_price);
         }
         if($request->filled('min_price')){
            $query->where('price', '>=' , $request->min_price);
         }
         if($request->sort === 'price_asc'){
            $query->orderBy('price');
         }elseif ($request->sort === 'price_desc'){
            $query->orderByDesc('price');
         }else{
            $query->latest();
         }
         return $query->paginate(10)->withQueryString();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProductRequest $request)
    {
        $product= Product::create($request->validated());

        return response()->json([
            'product'=>new ProductResource($product)
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        return response()->json([
            'product'=>new ProductResource($product->load('category'))
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProductRequest $request, Product $product)
    {
        
        $product->update($request->validated());

        return response()->json([
            'product'=>new ProductResource($product->load('category'))
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
         $product->delete();
         return response()->noContent();
    }
}
