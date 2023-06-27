<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\ReviewController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::get('categories', [CategoryController::class, 'index']);
Route::get('categories/{uuid}/products', [ProductController::class, 'index']);

Route::group(['prefix' => 'auth'], function () {
    Route::post('login', [AuthController::class, 'login']);
    Route::post('register', [AuthController::class, 'register']);
});

Route::group(['middleware' => 'auth:api'], function () {
    Route::group(['prefix' => 'auth'], function () {
        Route::get('/me', [AuthController::class, 'me']);
        Route::post('/logout', [AuthController::class, 'logout']);
        Route::put('/edit', [AuthController::class, 'update']);
        Route::put('/change-type', [AuthController::class, 'changeUserType']);
    });

    Route::group(['prefix' => 'products'], function () {
            Route::post('', [ProductController::class, 'store']);
            Route::put('/{uuid}', [ProductController::class, 'update']);
            Route::delete('/{uuid}', [ProductController::class, 'destroy']);
    });

    Route::group(['prefix' => 'categories'], function () {
            Route::post('', [CategoryController::class, 'store']);
            Route::put('/{uuid}', [CategoryController::class, 'update']);
            Route::delete('/{uuid}', [CategoryController::class, 'destroy']);
    });

    Route::group(['prefix' => 'reviews'], function () {
            Route::get('', [ReviewController::class, 'index']);
            Route::get('/{uuid}', [ReviewController::class, 'show']);
            Route::get('/product/{uuid}', [ReviewController::class, 'allReviewsByProduct']);
            Route::post('', [ReviewController::class, 'store']);
            Route::delete('/{uuid}', [ReviewController::class, 'destroy']);
    });

    Route::resource('orders', OrderController::class);
});
