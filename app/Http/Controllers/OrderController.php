<?php

namespace App\Http\Controllers;

use App\Enums\DeliveryType;
use App\Enums\OrderStatus;
use App\Enums\PaymentStatus;
use App\Enums\PaymentType;
use App\Http\Requests\OrderCreateRequest;
use App\Models\DeliveryAddress;
use App\Models\OnlinePayment;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;


class OrderController extends Controller
{
    public function index()
    {
        $products = Product::with('product_images')->get();
        return view('order', ['products' => $products]);
    }

    public function store(OrderCreateRequest $request): void
    {
        $requestData = $request->all();
        $paymentTotal = 0;

        foreach ($requestData['orderItems'] as $orderItem) {
            $product_id = $orderItem['productId'];
            $quantity = $orderItem['quantity'];

            $price = DB::table('products')
                ->where('id', $product_id)
                ->value('price');

            $paymentTotal += $price * $quantity;
        }

        $order = new Order;

        $order->user_id = Auth::id();
        $order->delivery_type = $requestData['deliveryType'];
        $order->payment_type = $requestData['paymentType'];
        $order->payment_total = $paymentTotal;
        $order->delivery_date = $requestData['deliveryDate'];
        $order->order_status = OrderStatus::PENDING->value;

        if (!$order->save()) {
            //todo
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
                //todo
            }
        }

        if ($requestData['paymentType'] === PaymentType::STRIPE->value) {
            $payment = new OnlinePayment();

            $payment->order_id = $order->id;
            $payment->status = PaymentStatus::DUE->value;

            if (!$payment->save()) {
                //todo
            }
        }

        if ($requestData['deliveryType'] === DeliveryType::HOME->value) {
            $address = new DeliveryAddress();

            $address->order_id = $order->id;
            $address->user_address_id = 1;

            if (!$address->save()) {
                //todo
            }
        }
    }


    //todo: use session to store cart items till the end of the day of the creation of that cart
}
