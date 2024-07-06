<?php

namespace App\Services;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\Cart;
use App\Http\Requests\CartRequest;

class CartService
{
    public function getCartItems()
    {
        return Cart::with(['product.image'])->where('user_id', auth()->id())->paginate(12);
    }

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

    public function createCart(CartRequest $request)
    {
        $userId = auth()->id();

        DB::transaction(function () use ($request, $userId) {
            Cart::create([
                'user_id' => $userId,
                'product_id' => $request->product_id,
                'quantity' => $request->quantity,
            ]);
        });

        Log::info('cart create succeeded');
    }

    public function getCartItem($id)
    {
        return Cart::with(['product.image'])->findOrFail($id);
    }

    public function updateCart(CartRequest $request, $id)
    {
        $cart = Cart::findOrFail($id);
        $cart->quantity = $request->quantity;

        DB::transaction(function () use ($cart) {
            if ($cart->isDirty()) {
                $cart->save();
            }
        });

        Log::info('cart update succeeded');
    }

    public function deleteCart($id)
    {
        $cart = Cart::findOrFail($id);

        DB::transaction(function () use ($cart) {
            $cart->delete();
        });

        Log::info('cart delete succeeded');
    }
}
