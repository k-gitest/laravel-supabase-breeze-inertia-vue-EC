<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ViewingHistory;

class ViewingHistoryController extends Controller
{
    public function showProduct($productId)
    {
        $userId = auth()->id(); // 現在のユーザーID
        ViewingHistory::create([
            'user_id' => $userId,
            'product_id' => $productId,
            //'viewed_at' => now(),
        ]);

        // 商品詳細ページの表示ロジック
    }
}
