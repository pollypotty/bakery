<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    public function index(): Application|View|\Illuminate\Foundation\Application|Factory
    {
        $products = Product::with('product_images')->get();
        return view('products', ['products' => $products]);
    }
}
