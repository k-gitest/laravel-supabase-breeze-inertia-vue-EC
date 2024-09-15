<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use App\Services\ProductService;
use App\Services\ViewingHistoryService;

class ProductController extends Controller
{
    protected $productService;
    protected $viewingHistoryService;
  
    public function __construct(ProductService $productService, ViewingHistoryService $viewingHistoryService)
    {
        $this->productService = $productService;
        $this->viewingHistoryService = $viewingHistoryService;
    }
  
    /**
     * Display a listing of the resource.
     */
    public function index(): Response
    { 
      $result = $this->productService->getAllProducts();
      $search_price_ranges = config('constants.PRICE_RANGES');

      return Inertia::render('EC/ProductAllList',[
          "pagedata" => $result,
          'price_ranges' => $search_price_ranges,
          'filters'  => [
              'category_ids' => [],
              'q'  => "",
              'price_range' => [],
              'warehouse_check' => false,
              'sort_option' => "newest",
          ],
      ]);
      
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id): Response | RedirectResponse
    {
      try {
          $productDetails = $this->productService->getProductDetails($id);
          $this->viewingHistoryService->saveViewHistory(auth()->id(), $id);

          return Inertia::render('EC/ProductDetail', [
              "data" => $productDetails['product'],
              "isInCart" => $productDetails['isInCart'],
              "isInComment" => $productDetails['isInComment'],
              "isInFavorite" => $productDetails['isInFavorite'],
              "recommendedProducts" => $productDetails['recommendedProducts'],
          ]);
      } catch (\Exception $e) {
          return redirect("/");
      }
      
    }
}
