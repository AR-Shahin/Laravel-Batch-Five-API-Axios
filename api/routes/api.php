<?php

use App\Http\Controllers\ProductController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;



Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


# Product API

Route::prefix('products')->controller(ProductController::class)->group(function () {
    Route::get('index');
    Route::post('store');
    Route::get('{product}', 'show');
    Route::post('update/{product}', 'update');
    Route::delete('{product}', 'delete');
});
