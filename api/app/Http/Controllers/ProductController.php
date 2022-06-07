<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    function index()
    {
        return response()->json(
            Product::get()
        );
    }

    public function store(Request $request)
    {
        Product::create($request->only(['title', 'price']));
    }


    public function show($product)
    {
        return response()->json(
            Product::find($product)
        );
    }

    public function update(Request $request, $product)
    {
        $product = Product::find($product);
        $product->update($request->only(['title', 'price']));

        return $product;
    }

    public function delete($product)
    {
        Product::find($product)->delete();
    }
}
