<?php

namespace App\Services;

use App\Models\Product;
use App\Enums\SortOption;
use App\Enums\PriceRange;

class SearchService
{
    public function searchProducts(array $params)
    {
        // 入力が何であれ、確実に有効な Enum を取得する
        $option = SortOption::tryFrom($params['sort_option']) ?? SortOption::newest;
        // Enum からソート用の設定を取り出す
        [$sortField, $sortDirection] = $option->values();

        /*
        $categoryIds = $request->query('category_ids', []);
        $searchTerm = $request->query('q');
        $priceRangeKeys = $request->query('price_range', []);
        $warehouseCheck = $request->query('warehouse_check', false);
        */

        $query = Product::with(['category', 'image', 'favorite'])
            ->withCount('favorite')
            ->orderBy($sortField, $sortDirection); 

        if ($params['warehouse_check'] ?? false) {
            $query->withSum('stock', 'quantity');
        } else {
            $warehouseId = config('constants.NET_WAREHOUSE_ID');
            $query->withSum(['stock' => function ($query) use ($warehouseId) {
                if ($warehouseId) {
                    $query->where('warehouse_id', $warehouseId);
                }
            }], 'quantity');
        }

        if (!empty($params['category_ids'])) {
            $query->whereIn('category_id', $params['category_ids']);
        } else {
            $query->whereNotNull('category_id');
        }

        if ($searchTerm = ($params['q'] ?? null)) {
            $query->where('name', 'like', '%' . $searchTerm . '%');
        }

        if (!empty($params['price_range'])) {
            $query->where(function ($query) use ($params) {
                foreach ($params['price_range'] as $key) {
                    $priceRange = PriceRange::tryFrom($key);
                    if (!$priceRange) continue;
                    [$min, $max] = $priceRange->values();

                    if ($max === null) {
                        $query->orWhere('price_excluding_tax', '>=', $min);
                    } else {
                        $query->orWhereBetween('price_excluding_tax', [$min, $max]);
                    }
                }
            });
        }

        return $query->paginate(12)->withQueryString();
    }
}
