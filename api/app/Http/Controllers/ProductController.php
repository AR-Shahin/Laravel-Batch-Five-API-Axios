<?php

namespace App\Http\Controllers;

use App\Helper\File;
use App\Models\Product;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    function index()
    {
        try {
            $products = Product::latest()->get();
            if ($products->count() != 0) {
                return sendSuccessResponse($products);
            } else {
                return sendSuccessResponse([], "No Data found!");
            }
        } catch (Exception $e) {
            return sendErrorResponse("Database Not found", $e->getMessage(), 500);
        }
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => ['required', 'unique:products,title'],
            'price' => ['required', 'numeric'],
            'image' => ['required', 'mimes:png,jpg,jpeg']
        ]);

        // return $request->file('image')->extension();
        if ($validator->fails()) {
            return sendErrorResponse("Client Side Error!", $validator->errors(), 422);
        }

        try {

            $data = $validator->validated();
            $data['image'] = File::upload($request->file('image'), "product");

            $product = Product::create($data);
            return sendSuccessResponse($product, "Data Created Successfully!", 201);
        } catch (Exception $e) {
            return sendErrorResponse("Database Not found", $e->getMessage(), 500);
        }
    }


    public function show($product)
    {
        try {
            $product = Product::whereId($product)->first();
            if ($product) {
                return sendSuccessResponse($product);
            } else {
                return sendErrorResponse("Invalid ID", [], 422);
            }
        } catch (Exception $e) {
            return sendErrorResponse("Database Not found", $e->getMessage(), 500);
        }
    }

    public function update(Request $request, $product)
    {
        $file = $request->file('image');
        if ($file) {
            $validator = Validator::make($request->all(), [
                'title' => ['required', "unique:products,title,$product"],
                'price' => ['required', 'numeric'],
                'image' => ['required', 'mimes:png,jpg,jpeg']
            ]);
        } else {
            $validator = Validator::make($request->all(), [
                'title' => ['required', "unique:products,title,$product"],
                'price' => ['required', 'numeric']
            ]);
        }

        if ($validator->fails()) {
            return sendErrorResponse("Client Side Error!", $validator->errors(), 422);
        }
        try {
            $product = Product::whereId($product)->first();
            if ($product) {
                if ($file) {
                    $oldImage = $product->image;
                    $data = $validator->validated();
                    $data['image'] = File::upload($request->file('image'), "product");
                    $product =  $product->update($data);
                    File::deleteFile($oldImage);
                } else {
                    $product =  $product->update($validator->validated());
                }
                return sendSuccessResponse($product, "Data Updated Successfully!");
            } else {
                return sendErrorResponse("Invalid ID", [], 422);
            }
        } catch (Exception $e) {
            return sendErrorResponse("Database Not found", $e->getMessage(), 500);
        }
        $product = Product::find($product);
        $product->update($request->only(['title', 'price']));

        return $product;
    }

    public function delete($product)
    {
        try {
            $product = Product::whereId($product)->first();
            if ($product) {
                $img = $product->image;
                info($img);
                $product->delete();
                File::deleteFile($img);
                return sendSuccessResponse([], "Data Deleted Successfully!");
            } else {
                return sendErrorResponse("Invalid ID", [], 422);
            }
        } catch (Exception $e) {
            return sendErrorResponse("Database Not found", $e->getMessage(), 500);
        }
    }
}
