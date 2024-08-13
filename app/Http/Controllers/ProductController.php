<?php

namespace App\Http\Controllers;

use App\Services\ProductService;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;

class ProductController extends Controller
{
    protected ProductService $productService;

    public function __construct()
    {
        $this->productService = new ProductService();
    }
    public function index(): Application|View|\Illuminate\Foundation\Application|Factory
    {
        $products = $this->productService->getAllProducts();
        return view('products', ['products' => $products]);
    }
}
