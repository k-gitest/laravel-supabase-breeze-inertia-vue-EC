<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Http\Request;
use App\Http\Requests\SearchRequest;
use App\Services\SearchService;

class SearchController extends Controller
{
  protected $searchService;

  public function __construct(SearchService $searchService)
  {
      $this->searchService = $searchService;
  }
    
  public function index(SearchRequest $request): Response
  {
      $validated = $request->validated();
      $result = $this->searchService->searchProducts($validated);
      $search_price_ranges = config('constants.PRICE_RANGES');

      return Inertia::render('EC/ProductAllList', [
          "pagedata" => $result,
          'price_ranges' => $search_price_ranges,
          'filters'      => array_merge([
                'sort_option'     => 'newest',
                'category_ids'    => [],
                'q'               => '',
                'price_range'     => [],
                'warehouse_check' => false,
            ], $validated),
      ]);
  }
  
}
