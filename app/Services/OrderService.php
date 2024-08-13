<?php

namespace App\Services;

use App\Enums\DeliveryType;
use App\Enums\OrderStatus;
use App\Models\DeliveryAddress;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class OrderService
{
    /**
     * @throws Exception
     */
    public function createOrder(array $requestData, $duePaymentTotal): void
    {
        DB::beginTransaction();

        try {
            // save order
            $orderId = $this->saveOrder($requestData, $duePaymentTotal);

            // save order items
            $this->saveOrderItems($requestData['orderItems'], $orderId);

            // save delivery address if home delivery
            if ($requestData['deliveryType'] === DeliveryType::HOME->value) {
                $this->saveDeliveryAddress($requestData['addressId'], $orderId);
            }

            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();

            Log::error('Saving order failed: ' . $e->getMessage(), ['exception' => $e]);
            throw $e;
        }
    }

    public function calculatePaymentTotal($orderItems): float
    {
        $duePaymentTotal = 0;

        foreach ($orderItems as $orderItem) {
            $price = Product::findOrFail($orderItem['productId'])->price;

            $duePaymentTotal += $price * $orderItem['quantity'];
        }

        return $duePaymentTotal;
    }

    /**
     * @throws Exception
     */
    public function saveOrder($requestData, $duePaymentTotal): int
    {
        $data = [
            'user_id' => Auth::id(),
            'delivery_type' => $requestData['deliveryType'],
            'payment_type' => $requestData['paymentType'],
            'payment_total' => $duePaymentTotal,
            'delivery_date' => $requestData['deliveryDate'],
            'order_status' => OrderStatus::PENDING->value,
        ];

        $order = Order::create($data);

        if (!$order) {
            throw new Exception('Failed to create order');
        }

        return $order->id;
    }

    /**
     * @throws Exception
     */
    public function saveOrderItems($orderItems, $orderId): void
    {
        foreach ($orderItems as $orderItem) {

            $price = DB::table('products')
                ->where('id', $orderItem['productId'])
                ->value('price');

            $data = [
                'order_id' => $orderId,
                'product_id' => $orderItem['productId'],
                'quantity' => $orderItem['quantity'],
                'price' => $price,
            ];

            $orderItem = OrderItem::create($data);

            if (!$orderItem) {
                throw new Exception('Failed to create order item');
            }
        }
    }

    /**
     * @throws Exception
     */
    public function saveDeliveryAddress($orderId, $addressId): void
    {
        $data = [
            'order_id' => $orderId,
            'address_id' => $addressId,
        ];

        $deliveryAddress = DeliveryAddress::create($data);

        if (!$deliveryAddress) {
            throw new Exception('Failed to create delivery address');
        }
    }
}
