<?php

namespace App\Http\Controllers\Admin;

use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use App\Models\Image;
use App\Services\Admin\AdminProductService;
use App\Http\Requests\Admin\AdminProductRequest;
use Log;

class AdminProductController extends Controller
{  
    protected $adminProductService;
  
    public function __construct(AdminProductService $adminProductService)
    {
        $this->adminProductService = $adminProductService;
    }
  
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): Response
    {
      $id = config('constants.NET_WAREHOUSE_ID');
      $search_price_ranges = config('constants.PRICE_RANGES');

      $data = $this->adminProductService->getAllProducts($id, $search_price_ranges);

      return Inertia::render('EC/Admin/ProductAllList', [
          'pagedata' => $data,
          'price_ranges' => $search_price_ranges,
          'filters' => [
              'category_ids' => [],
              'q' => "",
              'price_range' => [],
              'warehouse_check' => false,
              'sort_option' => "newest",
          ],
      ]);

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): Response
    {
      $data = Category::all();
      
      return Inertia::render('EC/Admin/ProductRegister',[
          "data" => $data,
      ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(AdminProductRequest $request, Product $product): RedirectResponse|bool
    {     
      try {
          $this->adminProductService->createProduct($request, $product);
      } catch (\Exception $e) {
          report($e);
          return false;
      }
      
      return redirect()->route('admin.product.index')->with('success', 'Product created');
      
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product, $id): Response | RedirectResponse
    {
      $data = Product::with(['category', 'image'])->findOrFail($id);

      return inertia::render('EC/Admin/ProductDetail',[
          "data" => $data,
      ]);

    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product, $id): Response
    {
      $data = Product::with('category', 'image')->findOrFail($id);
      
      return inertia::render('EC/Admin/ProductEdit',[
          "data" => $data,
      ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(AdminProductRequest $request, $id): RedirectResponse|bool
    {
      try {
          $this->adminProductService->updateProduct($request, $id);
      } catch (\Exception $e) {
          report($e);
          return false;
      }
      
      return redirect()->route('admin.product.edit', $id)->with('success', '更新しました');
      
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product, $id): RedirectResponse|bool
    { 
      try {
          $this->adminProductService->deleteProduct($id);
      } catch (\Exception $e) {
          report($e);
          return false;
      }
      
      return redirect('admin/product')->with('success', '削除しました');
    }

    public function bulkDestroy(Product $product, Request $request): RedirectResponse|bool
    {      
      $request->validate([
          "ids" => "required|array",
          'ids.*' => 'integer',
      ]);
      
      $selectedItems = $request->ids;

      try {
          $this->adminProductService->bulkDeleteProducts($selectedItems);
      } catch (\Exception $e) {
          report($e);
          return false;
      }
  
      return redirect()->back()->with('success', '削除しました');
    }
  
}


