<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Http\Request;
use App\Models\Product;

class SearchController extends Controller
{
  public function index(Request $request): Response
  {
    $sortOptions = config('constants.SORT_OPTIONS');
    $sort = $request->query('sort_option', 'newest');
    $sortOption = $sortOptions[$sort] ?? $sortOptions['newest'];
    
    $categoryIds = $request->query('category_ids', []);
    $searchTerm = $request->query('q');
    $priceRangeKeys = $request->query('price_range', []);
    $warehouseCheck = $request->query('warehouse_check', false);
    
    $query = Product::with(['category', 'image', 'favorite', 'stock'])->orderBy($sortOption[0], $sortOption[1])->withSum('stock', 'quantity')->withCount('favorite');

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

            if ($max === null) {
                $query->orWhere('price_excluding_tax', '>=', $min);
            } else {
                $query->orWhereBetween('price_excluding_tax', [$min, $max]);
            }
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

    $result = $query->paginate(12)->withQueryString();
    
    $search_price_ranges = config('constants.PRICE_RANGES');
    
    return inertia::render('EC/ProductAllList',[
      "pagedata" => $result,
      'price_ranges' => $search_price_ranges,
      'filters'  => [
           'category_ids' => $categoryIds,
           'q'  => $searchTerm,
           'price_range' => $priceRangeKeys,
           'warehouse_check' => $warehouseCheck,
           'sort_option' => $sort,
      ],
    ]);
  }
  
}
