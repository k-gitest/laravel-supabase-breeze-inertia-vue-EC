<?php

namespace App\Services\Admin;

use App\Models\Product;
use App\Enums\PriceRange;
use Illuminate\Http\Request;

class AdminSearchService
{
    public function searchProducts(Request $request)
    {
        $categoryIds = $request->query('category_ids', []);
        $searchTerm = $request->query('q');
        $priceRangeKeys = $request->query('price_range', []);
        $warehouseCheck = $request->query('warehouse_check', false);

        $query = Product::with(['image', 'category'])->orderBy('created_at', 'desc');

        if (!empty($categoryIds)) {
            $query->where(function ($query) use ($categoryIds) {
                $query->whereIn('category_id', $categoryIds);
            });
        }

        if ($searchTerm) {
            $query->where(function ($query) use ($searchTerm) {
                $query->where('name', 'like', '%' . $searchTerm . '%');
            });
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

        return $query->paginate(10)->withQueryString();
    }
}
