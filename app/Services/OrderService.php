<?php

namespace App\Services;

use App\Models\Order;
use App\Models\OrderItem;

class OrderService
{
    public function getOrderList($userId)
    {
        return Order::with(['orderItems'])->where('user_id', $userId)->paginate(10);
    }

    public function getOrderDetails($orderId, $userId)
    {
        return OrderItem::with(['product.image'])->where('order_id', $orderId)->where('user_id', $userId)->get();
    }
}
