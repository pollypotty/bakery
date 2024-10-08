<?php

use App\Http\Controllers\Admin\AdminAuthenticationController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AdminProductController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Auth\OAuthController;
use App\Http\Controllers\Auth\RegistrationController;
use App\Http\Controllers\Order\CartController;
use App\Http\Controllers\Order\OrderController;
use App\Http\Controllers\Product\ProductController;
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

Route::get('/products', [ProductController::class, 'index']);

Route::prefix('admin')->group(function () {
    Route::get('/login', [AdminAuthenticationController::class, 'index'])->name('admin.login');

    Route::post('/login', [AdminAuthenticationController::class, 'login']);

    Route::middleware('admin')->group(function () {
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('admin.dashboard');

        Route::get('/', [AdminDashboardController::class, 'index'])->name('admin.dashboard');

        Route::post('/logout', [AdminAuthenticationController::class, 'logout'])->name('admin.logout');

        Route::get('/products', [ProductController::class, 'index']);
    });
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

    Route::get('/cart', [CartController::class, 'index']);
});
