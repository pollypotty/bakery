<?php

namespace App\Http\Controllers;

use App\Enums\DeliveryType;
use App\Enums\OrderStatus;
use App\Http\Requests\OrderCreateRequest;
use App\Models\DeliveryAddress;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Exception;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;


class OrderController extends Controller
{
    public function index(): Factory|Application|View|\Illuminate\Contracts\Foundation\Application
    {
        $products = Product::with('product_images')->get();
        return view('order', ['products' => $products]);
    }

    public function store(OrderCreateRequest $request): \Illuminate\Http\JsonResponse
    {
        $requestData = $request->all();
        $duePaymentTotal = 0;

        foreach ($requestData['orderItems'] as $orderItem) {
            $product_id = $orderItem['productId'];
            $quantity = $orderItem['quantity'];

            $price = DB::table('products')
                ->where('id', $product_id)
                ->value('price');

            $duePaymentTotal += $price * $quantity;
        }

        DB::beginTransaction();

        try {
            if (abs($duePaymentTotal - (float) $requestData['paymentTotal']) > 0.01) {
                throw new Exception(config('messages.amount_mismatch'));
            }

            $order = new Order;

            $order->user_id = Auth::id();
            $order->delivery_type = $requestData['deliveryType'];
            $order->payment_type = $requestData['paymentType'];
            $order->payment_total = $duePaymentTotal;
            $order->delivery_date = $requestData['deliveryDate'];
            $order->order_status = OrderStatus::PENDING->value;

            if (!$order->save()) {
                throw new Exception(config('messages.order_save_fail'));
            }

            foreach ($requestData['orderItems'] as $orderItem) {
                $product_id = $orderItem['productId'];
                $quantity = $orderItem['quantity'];

                $price = DB::table('products')
                    ->where('id', $product_id)
                    ->value('price');

                $item = new OrderItem();

                $item->order_id = $order->id;
                $item->product_id = $product_id;
                $item->quantity = $quantity;
                $item->price_per_item = $price;

                if (!$item->save()) {
                    throw new Exception(config('messages.item_save_fail'));
                }
            }

            if ($requestData['deliveryType'] === DeliveryType::HOME->value) {
                $address = new DeliveryAddress();

                $address->order_id = $order->id;
                $address->user_address_id = $requestData['addressId'];

                if (!$address->save()) {
                    throw new Exception(config('messages.address_save_fail'));
                }
            }

            DB::commit();

            return response()->json(['success' => true, 'message' => config('messages.order_saved')]);
        } catch (Exception $e) {
            DB::rollBack();

            Log::error($e->getMessage(), ['exception' => $e]);

            return response()->json(['success' => false, 'message' => config('messages.order_save_failed')]);
        }
    }
}
