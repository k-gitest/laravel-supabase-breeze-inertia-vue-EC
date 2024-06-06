<?php

namespace App\Http\Controllers\Admin;

use Inertia\Response;
use Inertia\Inertia;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;

class AdminSearchController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): Response
    {
        $categoryIds = $request->query('category_ids', []);
        $searchTerm = $request->query('q');
        $priceRangeKeys = $request->query('price_range', []);
        $warehouseCheck = $request->query('warehouse_check', false);

        $query = Product::with(['image', 'category'])->orderBy('created_at', 'desc');

        if(!empty($categoryIds)){
            $query->whereIn('category_id', $categoryIds);
        }

        if($searchTerm){
            $query->where('name', 'like', '%' . $searchTerm . '%');
        }

        if(!empty($priceRangeKeys)){
            $priceRanges = config('constants.PRICE_RANGES');

            $minPrice = null;
            $maxPrice = null;

            foreach ($priceRangeKeys as $key) {
                [$min, $max] = $priceRanges[$key];
                $minPrice = $minPrice === null ? $min : min($minPrice, $min);
                $maxPrice = $maxPrice === null ? $max : max($maxPrice, $max);
            }

            if ($maxPrice === null) {
                $query->where('price_excluding_tax', '>=', $minPrice);
            } else {
                $query->whereBetween('price_excluding_tax', [$minPrice, $maxPrice]);
            }
        }

        if($warehouseCheck === "true"){
            $query->withSum('stock', 'quantity');
        } else {
            $warehouseId = config('constants.NET_WAREHOUSE_ID');
            $query->withSum(['stock' => function ($query) use ($warehouseId) {
                if ($warehouseId) {
                    $query->where('warehouse_id', $warehouseId);
                }
            }], 'quantity');
        }

        $result = $query->paginate(10)->withQueryString();

        $search_price_ranges = config('constants.PRICE_RANGES');

        return inertia::render('EC/Admin/ProductAllList', [
              'pagedata' => $result,
              'price_ranges' => $search_price_ranges,
              'filters'  => [
                  'category_ids' => $categoryIds,
                  'q'  => $searchTerm,
                  'warehouse_check' => $warehouseCheck,
                  'price_range' => $priceRangeKeys,
              ],
          ]);
    }

}
