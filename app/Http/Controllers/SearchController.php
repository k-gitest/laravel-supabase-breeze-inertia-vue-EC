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
      $result = $this->searchService->searchProducts($request);
      $search_price_ranges = config('constants.PRICE_RANGES');

      return Inertia::render('EC/ProductAllList', [
          "pagedata" => $result,
          'price_ranges' => $search_price_ranges,
          'filters'  => [
              'category_ids' => $request->query('category_ids', []),
              'q'  => $request->query('q'),
              'price_range' => $request->query('price_range', []),
              'warehouse_check' => $request->query('warehouse_check', false),
              'sort_option' => $request->query('sort_option', 'newest'),
          ],
      ]);
  }
  
}
