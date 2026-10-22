<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user' , function(Request $request){
    return $request->user();
})->middleware('auth:sanctum');

Route::apiResource('categories' , CategoryController::class)->only('index' , 'show' )->middleware('auth:sanctum');
Route::apiResource('products' , ProductController::class)->only('index' , 'show')->middleware('auth:sanctum');

Route::post('/register' , [AuthController::class , 'register']);
Route::post('/login' , [AuthController::class , 'login']);
//Route::post('/logout' , [AuthController::class , 'logout'])->middleware('auth:sanctum');

//Route::get('/orders', [OrderController::class , 'index'])->middleware('auth:sanctum');
//Route::post('/orders', [OrderController::class , 'store'])->middleware("auth:sanctum");

Route::middleware('auth:sanctum')->group(function(){
    Route::post('/logout' , [AuthController::class , 'logout']);
    Route::get('/orders', [OrderController::class , 'index']);
    Route::post('/orders', [OrderController::class , 'store']);

    Route::middleware('admin')->group(function(){
        Route::apiResource('categories' , CategoryController::class)->except(['index' , 'show']);

        Route::apiResource('products' , ProductController::class)->except(['index' , 'show']);
    });
});