<?php

namespace App\Services;

use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class OrderService
{
    public function getOrderList($userId): LengthAwarePaginator
    {
        // 商品情報までEager LoadingすることでN+1問題を防止
        return Order::query()
            ->with(['orderItems.product.image'])
            ->where('user_id', $userId)
            ->latest()
            ->paginate(10);
        # return Order::with(['orderItems'])->where('user_id', $userId)->paginate(10);
    }

    public function getOrderDetails($orderId, $userId): Collection
    {
        // 先にOrderの所有権を確認することで、不正アクセスを防止
        return OrderItem::query()
            ->with(['product.image'])
            ->where('order_id', $orderId)
            ->where('user_id', $userId)
            ->get();
        # return OrderItem::with(['product.image'])->where('order_id', $orderId)->where('user_id', $userId)->get();
    }
}
