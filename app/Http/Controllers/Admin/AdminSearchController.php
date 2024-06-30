<?php

namespace App\Http\Controllers\Admin;

use Inertia\Response;
use Inertia\Inertia;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Enums\SortOption;
use App\Enums\PriceRange;
use App\Http\Requests\Admin\AdminSearchRequest;

class AdminSearchController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(AdminSearchRequest $request): Response
    {
        $categoryIds = $request->query('category_ids', []);
        $searchTerm = $request->query('q');
        $priceRangeKeys = $request->query('price_range', []);
        $warehouseCheck = $request->query('warehouse_check', false);

        $query = Product::with(['image', 'category'])->orderBy('created_at', 'desc');

        if(!empty($categoryIds)){
            $query->where(function ($query) use ($categoryIds) {
                $query->whereIn('category_id', $categoryIds);
            });
        }

        if($searchTerm){
            $query->where(function ($query) use ($searchTerm) {
                $query->where('name', 'like', '%' . $searchTerm . '%');
            });
        }

        if(!empty($priceRangeKeys)){
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
