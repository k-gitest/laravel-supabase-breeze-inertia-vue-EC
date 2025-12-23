<?php

namespace App\Services;

use App\Models\Product;
use App\Enums\SortOption;
use App\Enums\PriceRange;

class SearchService
{
    public function searchProducts($request)
    {
        // 入力が何であれ、確実に有効な Enum を取得する
        $option = SortOption::tryFrom($request->query('sort_option')) ?? SortOption::newest;
        // Enum からソート用の設定を取り出す
        [$sortField, $sortDirection] = $option->values();

        $categoryIds = $request->query('category_ids', []);
        $searchTerm = $request->query('q');
        $priceRangeKeys = $request->query('price_range', []);
        $warehouseCheck = $request->query('warehouse_check', false);

        $query = Product::with(['category', 'image', 'favorite', 'stock'])
            ->orderBy($sortField, $sortDirection)
            ->withSum('stock', 'quantity')
            ->withCount('favorite');

        if (!empty($categoryIds)) {
            $query->whereIn('category_id', $categoryIds);
        } else {
            $query->whereNotNull('category_id');
        }

        if ($searchTerm) {
            $query->where('name', 'like', '%' . $searchTerm . '%');
        }

        if (!empty($priceRangeKeys)) {
            $query->where(function ($query) use ($priceRangeKeys) {
                foreach ($priceRangeKeys as $key) {
                    $priceRange = PriceRange::fromRequestKey($key);
                    [$min, $max] = $priceRange->values();

                    if ($max === null) {
                        $query->orWhere('price_excluding_tax', '>=', $min);
                    } else {
                        $query->orWhereBetween('price_excluding_tax', [$min, $max]);
                    }
                }
            });
        }

        if ($warehouseCheck === "true") {
            $query->withSum('stock', 'quantity');
        } else {
            $warehouseId = config('constants.NET_WAREHOUSE_ID');
            $query->withSum(['stock' => function ($query) use ($warehouseId) {
                if ($warehouseId) {
                    $query->where('warehouse_id', $warehouseId);
                }
            }], 'quantity');
        }

        return $query->paginate(12)->withQueryString();
    }
}
