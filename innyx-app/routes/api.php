<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;


Route::post('login', [AuthController::class, 'login']);
Route::post('register', [AuthController::class, 'register']);


Route::middleware('auth:api')->group(function () {
    Route::get('preview', [UserController::class, 'get_preview']);
    Route::prefix('products')->controller(ProductController::class)->group(function () {
        Route::get('/', 'index');
        Route::post('/', 'store');
        Route::get('{product}', 'show');
        Route::post('{product}', 'update');
        Route::delete('{product}', 'destroy');
    });

    Route::prefix('categories')->middleware('is_admin')->controller(CategoryController::class)->group(function () {
        Route::get('/', 'index')->withoutMiddleware('is_admin');
        Route::post('/', 'store');
        Route::delete('/{id}', 'destroy');
    });

    Route::prefix('users')->middleware(['auth:api', 'is_admin'])->group(function () {
        Route::get('/', [UserController::class, 'index']);
        Route::post('/', [UserController::class, 'store']);
        Route::put('/{user}', [UserController::class, 'update']);
        Route::delete('/{user}', [UserController::class, 'destroy']);
        Route::post('/reset_password', [UserController::class, 'resetPass']);
        Route::post('/set_password', [UserController::class, 'setPass'])->withoutMiddleware('is_admin');
    });

});
