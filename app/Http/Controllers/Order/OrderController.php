<?php

namespace App\Http\Controllers\Order;

use App\Http\Controllers\Controller;
use App\Http\Requests\OrderCreateRequest;
use App\Services\OrderService;
use App\Services\ProductService;
use Exception;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\JsonResponse;

class OrderController extends Controller
{
    protected ProductService $productService;
    protected OrderService $orderService;

    public function __construct()
    {
        $this->productService = new ProductService();
        $this->orderService = new OrderService();
    }

    public function index(): Factory|Application|View|\Illuminate\Contracts\Foundation\Application
    {
        $products = $this->productService->getAllProducts();
        return view('order.order', ['products' => $products]);
    }

    public function store(OrderCreateRequest $request): JsonResponse
    {
        $requestData = $request->all();

        $duePaymentTotal = $this->orderService->calculatePaymentTotal($requestData['orderItems']);

        try {
            if (abs($duePaymentTotal - (float)$requestData['paymentTotal']) > 0.01) {
                throw new Exception(config('messages.amount_mismatch'));
            }

            $this->orderService->saveOrder($requestData, $duePaymentTotal);

            return response()->json(['success' => true, 'message' => config('messages.order_saved')]);
        } catch (\Exception) {

            return response()->json(['success' => false, 'message' => config('messages.order_save_failed')]);
        }
    }
}
