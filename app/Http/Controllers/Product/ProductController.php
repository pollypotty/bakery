<?php

namespace App\Http\Controllers\Product;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProductRequest;
use App\Services\ProductService;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

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

        if (session()->has('admin_logged_in')) {
            return view('admin.admin_products', ['products' => $products]);
        }

        return view('products', ['products' => $products]);
    }

    public function update(ProductRequest $request, $productId): JsonResponse
    {
        try {
            $requestData = $request->all();

            $updatedProduct = $this->productService->update($requestData, $productId);

            return response()->json($updatedProduct, 200);

        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], $e->getCode() ?: 500);
        }
    }

    public function destroy($productId): JsonResponse
    {
        try {
            $this->productService->destroy($productId);

            return response()->json(null, 204);

        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], $e->getCode() ?: 500);
        }
    }

    public function store(ProductRequest $request): JsonResponse
    {
        try {
            $product = $this->productService->store($request->all());
            return response()->json($product, 200);

        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], $e->getCode() ?: 500);
        }
    }
}
