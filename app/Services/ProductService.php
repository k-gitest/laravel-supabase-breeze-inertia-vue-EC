<?php

namespace App\Services;

use App\Models\Product;
use App\Models\Cart;
use App\Models\Comment;
use App\Models\Favorite;
use App\Models\ProductVector;
use Pgvector\Laravel\Distance;
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

        $product = Product::with(['category', 'image', 'comment', 'stock', 'favorite', 'vector'])
            ->withSum('stock', 'quantity')
            ->findOrFail($id);

        $embedding = $product->vector->embedding;

        $nearestNeighbors = ProductVector::query()
            ->nearestNeighbors('embedding', $embedding, Distance::L2)
            ->where('product_id', '!=', $id) 
            ->take(5)
            ->get();

        $productIds = $nearestNeighbors->pluck('product_id');
        $recommendedProducts = Product::whereIn('id', $productIds)
            ->with(['category', 'image', 'stock', 'favorite'])
            ->withSum('stock', 'quantity')  
            ->get();

        return [
            'product' => $product,
            'isInCart' => $isInCart,
            'isInComment' => $isInComment,
            'isInFavorite' => $isInFavorite,
            'recommendedProducts' => $recommendedProducts,
        ];
    }
}
