<?php

namespace App\Services;

use App\Models\ViewingHistory;
use App\Models\Product;
use App\Models\UserVector;

class ViewingHistoryService
{
    public function saveViewHistory($userId, $productId)
    {
        if ($userId === null) {
            return;
        }
        
        ViewingHistory::create([
            'user_id' => $userId,
            'product_id' => $productId,
        ]);

        // 履歴保存後にユーザーのベクトル化を実行
        //$this->vectorizeUserHistory($userId);
    }

    private function vectorizeUserHistory($userId)
    {
        // ユーザーの全ての閲覧履歴を取得
        $history = ViewingHistory::where('user_id', $userId)
            ->with('product') // 商品データも一緒に取得
            ->get();

        // 商品のベクトルデータを収集
        $vectors = $history->map(function ($item) {
            return json_decode($item->product->vector_data, true); // 商品のベクトルデータを取得
        })->toArray();

        if (empty($vectors)) {
            return; // ベクトルがない場合は処理をスキップ
        }

        // ユーザーのベクトルを計算（例: 平均ベクトル）
        $userVector = array_map(function (...$values) {
            return array_sum($values) / count($values); // 各次元の平均を取る
        }, ...$vectors);

        // 既に同じタイプのベクトルデータがあるか確認
        $existingVector = UserVector::where('user_id', $userId)
                                    ->where('type', $type)
                                    ->first();

        if ($existingVector) {
            // 既存のレコードがある場合は更新
            $existingVector->vector = $userVector;
            $existingVector->save();
        } else {
            // 既存のレコードがない場合は新規作成
            UserVector::create([
                'user_id' => $userId,
                'type' => $type, // 'viewing_history' や 'interests' など
                'vector' => $userVector,
            ]);
        }
    }
}
