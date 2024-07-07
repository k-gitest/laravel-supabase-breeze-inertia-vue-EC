<?php

namespace App\Services;

use App\Models\Product;
use App\Models\Cart;
use App\Models\Comment;
use App\Models\Favorite;
use Illuminate\Support\Facades\Auth;

class ProductService
{
    public function getAllProducts()
    {
        return Product::with(['category', 'image', 'favorite', 'stock'])
            ->orderBy('created_at', 'desc')
            ->withSum('stock', 'quantity')
            ->paginate(12);
    }

    public function getProductDetails($id)
    {
        $authCheck = Auth::check();
        $isInCart = false;
        $isInComment = false;
        $isInFavorite = false;

        if ($authCheck) {
            $userId = auth()->user()->id;
            $isInCart = Cart::where('user_id', $userId)->where('product_id', $id)->exists();
            $isInComment = Comment::where('user_id', $userId)->where('product_id', $id)->exists();
            $isInFavorite = Favorite::where('user_id', $userId)->where('product_id', $id)->exists();
        }

        $product = Product::with(['category', 'image', 'comment', 'stock', 'favorite'])
            ->withSum('stock', 'quantity')
            ->findOrFail($id);

        return [
            'product' => $product,
            'isInCart' => $isInCart,
            'isInComment' => $isInComment,
            'isInFavorite' => $isInFavorite,
        ];
    }
}
