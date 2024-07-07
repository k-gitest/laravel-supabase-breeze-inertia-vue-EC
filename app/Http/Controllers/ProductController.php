<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use App\Services\ProductService;

class ProductController extends Controller
{
    protected $productService;
  
    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
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

          return Inertia::render('EC/ProductDetail', [
              "data" => $productDetails['product'],
              "isInCart" => $productDetails['isInCart'],
              "isInComment" => $productDetails['isInComment'],
              "isInFavorite" => $productDetails['isInFavorite'],
          ]);
      } catch (\Exception $e) {
          return redirect("/");
      }
      
    }
}
