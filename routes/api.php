<?php

use App\Http\Controllers\OrderController;
use App\Http\Controllers\UserAddressController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
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
});
