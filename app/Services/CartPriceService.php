<?php

namespace App\Services;

use App\Models\Cart;

class CartPriceService
{
    public function getTotalPrices($result)
    {
        $total_price_excluding_tax = 0;
        $total_price_including_tax = 0;

        foreach ($result as $item) {
            $total_price_excluding_tax += $item->quantity * $item->product->price_excluding_tax;
            $total_price_including_tax += $item->quantity * $item->product->price_including_tax;
        }

        return [
            'total_price_excluding_tax' => $total_price_excluding_tax,
            'total_price_including_tax' => $total_price_including_tax,
        ];
    }
}
