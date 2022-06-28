<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\Api\JWTUserAuthController;
use App\Http\Controllers\Api\JWTAdminAuthController;



Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


# Product API

Route::controller(ProductController::class)->group(function () {
    Route::get('products', 'index')->middleware('auth:user_api');
    Route::post('products', 'store');
    Route::get('products/{product}', 'show')->middleware('auth:admin_api');
    Route::post('products/update/{product}', 'update');
    Route::delete('products/{product}', 'delete');
});


# Auth API

Route::post('login', [AuthController::class, 'login']);
Route::post('register', [AuthController::class, 'register']);
Route::post('logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');


// Route::get('products', [ProductController::class, 'index']);


# Auth API User JWT

Route::controller(JWTUserAuthController::class)->prefix('user')->group(function(){
    Route::post('login', 'login');
    Route::post('register', 'register');
    Route::post('logout', 'logout')->middleware('auth:user_api');
});


# Auth API Admin JWT

Route::controller(JWTAdminAuthController::class)->prefix('admin')->group(function(){
    Route::post('login', 'login');
    Route::post('register', 'register');
    Route::post('logout', 'logout')->middleware('auth:admin_api');
});
