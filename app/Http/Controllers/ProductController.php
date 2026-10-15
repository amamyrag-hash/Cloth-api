<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductRequest;
use App\Http\Resources\ProductResource;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json([
            'products'=>ProductResource::collection(Product::with('category')->paginate())
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ProductRequest $request)
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
    public function update(Request $request, Product $product)
    {
        $data=$request->validate([
           'category_id'=>'require|exists:categories,id',
            'name'=>'require|string|max:255',
            'slug'=>'require|string|max:255|unique:products,slug' . $product->id,
            'description'=>'nullable|string',
            'price'=>'require|numeric|min::0',
            'stock'=>'intger|min:0',
            'image'=>'nullable|string'
        ]);
        $product->update($data);

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
