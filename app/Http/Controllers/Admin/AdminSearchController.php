<?php

namespace App\Http\Controllers\Admin;

use Inertia\Response;
use Inertia\Inertia;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Enums\SortOption;
use App\Enums\PriceRange;
use App\Services\Admin\AdminSearchService;
use App\Http\Requests\Admin\AdminSearchRequest;

class AdminSearchController extends Controller
{
    protected $adminSearchService;

    public function __construct(AdminSearchService $adminSearchService)
    {
        $this->adminSearchService = $adminSearchService;
    }
    
    /**
     * Display a listing of the resource.
     */
    public function index(AdminSearchRequest $request): Response
    {
        $result = $this->adminSearchService->searchProducts($request);

        $search_price_ranges = config('constants.PRICE_RANGES');

        return inertia::render('EC/Admin/ProductAllList', [
            'pagedata' => $result,
            'price_ranges' => $search_price_ranges,
            'filters' => [
                'category_ids' => $request->query('category_ids', []),
                'q' => $request->query('q'),
                'warehouse_check' => $request->query('warehouse_check', false),
                'price_range' => $request->query('price_range', []),
            ],
        ]);

    }

}
