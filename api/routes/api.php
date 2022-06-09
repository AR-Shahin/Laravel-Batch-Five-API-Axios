<?php

use App\Http\Controllers\ProductController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;



Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


# Product API

Route::controller(ProductController::class)->group(function () {
    Route::get('products', 'index');
    Route::post('products', 'store');
    Route::get('products/{product}', 'show');
    Route::post('products/update/{product}', 'update');
    Route::delete('products/{product}', 'delete');
});


// Route::get('products', [ProductController::class, 'index']);
