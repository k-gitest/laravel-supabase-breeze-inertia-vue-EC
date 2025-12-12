<?php

namespace App\Services;

use App\Models\Product;
use App\Models\Cart;
use App\Models\Comment;
use App\Models\Favorite;
use App\Models\ProductVector;
use Pgvector\Laravel\Distance;
use Illuminate\Support\Facades\Auth;
use Log;

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
        $product = Product::with(['category', 'image', 'comment', 'stock', 'favorite', 'vector'])
            ->withSum('stock', 'quantity')
            ->findOrFail($id);

        # $authCheck = Auth::check();
        $userId = Auth::id(); // Auth::check() && Auth::user()->id; と同等の動作
        $isInCart = false;
        $isInComment = false;
        $isInFavorite = false;

        if ($userId) {
            # $userId = auth()->user()->id;
            $isInCart = Cart::where('user_id', $userId)->where('product_id', $id)->exists();
            $isInComment = Comment::where('user_id', $userId)->where('product_id', $id)->exists();
            $isInFavorite = Favorite::where('user_id', $userId)->where('product_id', $id)->exists();
        }

        if (!$product->vector) {
            // ログを記録（データ不備を通知）
            Log::warning("Product ID {$id} is missing vector data. Cannot recommend neighbors.");
            
            // エラーにせず、空のコレクションを返してサービスを続行
            $recommendedProducts = collect([]); 
        } else {
            $embedding = $product->vector->embedding;

            $nearestNeighbors = ProductVector::query()
                ->nearestNeighbors('embedding', $embedding, Distance::L2)
                ->where('product_id', '!=', $id) 
                ->take(5)
                ->get();

            $productIds = $nearestNeighbors->pluck('product_id');
            $recommendedProducts = Product::whereIn('id', $productIds)
                ->with(['category', 'image', 'stock', 'favorite', 'comment'])
                ->withSum('stock', 'quantity')  
                ->get();
        }

        return [
            'product' => $product,
            'isInCart' => $isInCart,
            'isInComment' => $isInComment,
            'isInFavorite' => $isInFavorite,
            'recommendedProducts' => $recommendedProducts,
        ];
    }
}
