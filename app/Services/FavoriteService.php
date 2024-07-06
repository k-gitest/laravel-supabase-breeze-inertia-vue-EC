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

        try {
            DB::transaction(function () use ($request) {
                Favorite::create([
                    'user_id' => auth()->user()->id,
                    'product_id' => $request->product_id,
                ]);
            });
            Log::info('Favorite created');
        } catch (\Exception $e) {
            report($e);
            throw $e;
        }
    }

    public function removeFavorite($userId, $id)
    {
        $favorite = Favorite::where('user_id', $userId)->findOrFail($id);

        try {
            DB::transaction(function () use ($favorite) {
                $favorite->delete();
            });
            Log::info('Favorite deleted');
        } catch (\Exception $e) {
            report($e);
            throw $e;
        }
    }
}
