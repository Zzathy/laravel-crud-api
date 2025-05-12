<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return ProductResource::collection(Product::all());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProductRequest $request)
    {
        try {
            $filename = null;

            if ($request->image) {
                $image = $request->image;
                $filename = Carbon::now()->format('YmdHis') . '.' . $image->extension();
                $image->storeAs('images', $filename, 'public');
            }

            $product = Product::create([
                'name' => $request->name,
                'category_id' => $request->category_id,
                'description' => $request->description,
                'price' => $request->price,
                'image' => $filename
            ]);
        } catch (\Exception $e) {
            return response()->json(null, 500);
        }

        return ProductResource::make($product);
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        return ProductResource::make($product);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProductRequest $request, Product $product)
    {
        try {
            if ($request->hasFile('image')) {
                if ($product->image) {
                    Storage::disk('public')->delete('images/' . $product->image);
                }

                $image = $request->image;
                $filename = Carbon::now()->format('YmdHis') . '.' . $image->extension();
                $image->storeAs('images', $filename, 'public');

                $product->image = $filename;
            }

            $product->name = $request->name;
            $product->category_id = $request->category_id;
            $product->description = $request->description;
            $product->price = $request->price;

            $product->save();

            return ProductResource::make($product);
        } catch (\Exception $e) {
            return response()->json(null, 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        try {
            if ($product->image) {
                Storage::disk('public')->delete('images/' . $product->image);
            }

            $product->delete();
            
            return response()->json(null, 204);
        } catch (\Exception $e) {
            return response()->json(null, 500);
        }
    }
}
