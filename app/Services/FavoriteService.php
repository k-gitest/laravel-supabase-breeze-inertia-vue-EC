<?php

namespace App\Services;

use App\Models\Favorite;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class FavoriteService
{
    public function getFavoritesByUser($userId)
    {
        return Favorite::with(['product.image', 'product.category'])
            ->where('user_id', $userId)
            ->orderBy('updated_at', 'desc')
            ->paginate(12);
    }

    public function addFavorite(Request $request)
    {
        $request->validate([
            'product_id' => 'required|integer',
        ]);

        $userId = auth()->user()->id;
        $productId = $request->product_id;

        try {
            $favorite = DB::transaction(function () use ($request, $userId, $productId) {
                $result = Favorite::firstOrCreate([
                        'user_id' => $userId,
                        'product_id' => $productId
                    ]);
                return $result;
            });
            if ($favorite->wasRecentlyCreated) {
                Log::info('Favorite created', ['user_id' => $userId, 'product_id' => $productId]);
            } else {
                // 既に存在していた場合
                Log::warning('Attempted to add existing favorite.', ['user_id' => $userId, 'product_id' => $productId]);
            }
        } catch (\Exception $e) {
            report($e);
            throw $e;
        }
    }

    public function removeFavorite($userId, $id)
    {
        try {
            $deletedCount = Favorite::where('user_id', $userId)
                                    ->where('id', $id)
                                    ->delete(); 
    
            if ($deletedCount === 0) {
                // 削除対象がなければ、404レスポンスを自動生成させるための例外を投げる
                throw new \Illuminate\Database\Eloquent\ModelNotFoundException('Favorite not found or does not belong to user.');
            }
    
            Log::info('Favorite deleted', ['user_id' => $userId, 'id' => $id]);
        } catch (\Exception $e) {
            report($e);
            throw $e;
        }
    }
}
