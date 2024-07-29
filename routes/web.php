<?php

use App\Http\Controllers\CartController;
use App\Http\Controllers\OAuthController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('home');
});

Route::middleware('guest')->group(function () {
    Route::get('/registration', function () {
        return view('register');
    });

    Route::post('/registration', [UserController::class, 'register']);

    Route::get('/login', function () {
        return view('login');
    })->name('login');

    Route::post('/login', [UserController::class, 'login']);

    Route::get('/auth/google/login', [OAuthController::class, 'redirectToGoogleLogin']);

    Route::get('/auth/google/register', [OAuthController::class, 'redirectToGoogleRegister']);

    Route::get('/auth/google/callback', [OAuthController::class, 'handleGoogleCallback']);
});

Route::post('/logout', [UserController::class, 'logout']);

Route::middleware('auth')->group(function () {
    Route::get('/profile', function () {
        return view('profile');
    })->name('profile');

    Route::get('/order', [OrderController::class, 'index']);

    Route::get('/cart',  [CartController::class, 'index']);

    Route::get('/payment', [PaymentController::class, 'createPayment']);
});

Route::get('/products', [ProductController::class, 'index']);
