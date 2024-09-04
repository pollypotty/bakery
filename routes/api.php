<?php

use App\Http\Controllers\Order\OrderController;
use App\Http\Controllers\Order\PaymentController;
use App\Http\Controllers\Order\UserAddressController;
use App\Http\Controllers\Product\ProductController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('web')->group(function () {
    Route::get('/user_addresses', [UserAddressController::class, 'getUserAddresses']);
    Route::post('/user_addresses', [UserAddressController::class, 'store']);
    Route::post('/order', [OrderController::class, 'store']);
    Route::post('/create-payment-intent', [PaymentController::class, 'createPaymentIntent']);
});

Route::middleware('admin')->group(function () {
    Route::patch('/products/{product}', [ProductController::class, 'update']);
    Route::delete('/products/{product}', [ProductController::class, 'destroy']);
    Route::post('/products', [ProductController::class, 'store']);
});


