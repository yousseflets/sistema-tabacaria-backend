<?php

use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\CategoryController;
use Illuminate\Support\Facades\Route;

Route::apiResource('products', ProductController::class);
Route::apiResource('categories', CategoryController::class);

// products by category (ex: /api/products/category/2)
Route::get('products/category/{id}', [ProductController::class, 'byCategory']);

Route::options('{any}', function () {
	return response('', 200)
		->header('Access-Control-Allow-Origin', 'http://localhost:4200')
		->header('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS')
		->header('Access-Control-Allow-Headers', 'Content-Type, Authorization');
})->where('any', '.*');