<?php

namespace App\Http\Controllers\Order;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;

class CartController extends Controller
{
    public function index(): Factory|Application|View|\Illuminate\Contracts\Foundation\Application
    {
        $stripeKey = env('STRIPE_KEY');
        return view('order.cart', compact('stripeKey'));
    }
}
