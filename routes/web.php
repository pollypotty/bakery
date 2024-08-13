<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Auth\OAuthController;
use App\Http\Controllers\Auth\RegistrationController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
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
    Route::get('/registration', [RegistrationController::class, 'index'])->name('registration');

    Route::post('/registration', [RegistrationController::class, 'register']);

    Route::get('/login', [AuthController::class, 'index'])->name('login');

    Route::post('/login', [AuthController::class, 'login']);

    Route::get('/auth/google/login', [OAuthController::class, 'redirectToGoogleLogin']);

    Route::get('/auth/google/register', [OAuthController::class, 'redirectToGoogleRegister']);

    Route::get('/auth/google/callback', [OAuthController::class, 'handleGoogleCallback']);
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);

    Route::get('/profile', function () {
        return view('profile');
    })->name('profile');

    Route::get('/order', [OrderController::class, 'index']);

    Route::get('/cart',  [CartController::class, 'index']);
});

Route::get('/products', [ProductController::class, 'index']);
