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
use App\Services\Admin\AdminImageService;
use App\Http\Requests\Admin\AdminProductRequest;
use Log;

class AdminProductController extends Controller
{  
    protected $adminImageService;
  
    public function __construct(AdminImageService $adminImageService)
    {
        $this->adminImageService = $adminImageService;
    }
  
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): Response
    {
      $id = config('constants.NET_WAREHOUSE_ID');
      $search_price_ranges = config('constants.PRICE_RANGES');
      
      $result = Product::with(['image', 'category'])
        ->withSum(['stock' => function ($query) use ($id) {
                      if ($id) {
                          $query->where('warehouse_id', $id);
                      }
                  }],'quantity')
        ->orderBy('created_at', 'desc');
      
      $data = $result->paginate(10);

      return inertia::render('EC/Admin/ProductAllList', [
          'pagedata' => $data,
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
        DB::transaction(function () use ($request, $product) {
          $price_including_tax = $this->calculatePriceIncludingTax($request->price_excluding_tax, $request->tax_rate);

          $validateData = $request->validated();

          $validateData["price_including_tax"] = $price_including_tax;

          $result = $product->create($validateData);
          Log::info('Product created', ['name' => $result->name]);

          $product_id = $result->id;
          $filenames = $this->adminImageService->handleImageProductUploads($product_id, $request);
          Log::info('product image save success');

          try {
            $this->adminImageService->saveImages($filenames);
          }
          catch(\Exception $e){
            $this->adminImageService->deleteUploadImages($filenames, 'path');
            throw $e;
          }

        });
      }
      catch(\Exception $e) {
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
      if($data){
        return inertia::render('EC/Admin/ProductDetail',[
            "data" => $data,
        ]);
      } else {
        return redirect("/");
      }

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
        DB::transaction(function () use ($request, $id) {
          $product = Product::lockForUpdate()->findOrFail($id);
          $product->fill($request->validated());

          $price_including_tax = $this->calculatePriceIncludingTax($request->price_excluding_tax, $request->tax_rate);

          $product->price_including_tax = $price_including_tax;

          if ($product->isDirty()) {
                $product->save();
          }
          Log::info('Product updated.', ['id' => $product->id]);

          $product_id = $product->id;

          $latestNumber = $this->adminImageService->getLatestImageNumber($product_id);

          $filenames = $this->adminImageService->handleImageProductUploads($product_id, $request, $latestNumber);
          Log::info('Update Image uploaded.', ['id' => $product->id]);

          try{
            $this->adminImageService->saveImages($filenames);
            Log::info('Product updated.', ['id' => $product->id]);
          }
          catch(\Exception $e){
            $this->adminImageService->deleteUploadImages($filenames, 'path');
            throw $e;
          }
          
        });
      }
      catch (\Exception $e) {
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
      try{
        DB::transaction(function () use ($product, $id) {
          $product = Product::lockForUpdate()->findOrFail($id);
          
          $images = Image::where('product_id', $id)->pluck('path')->toArray();
          
          $product->delete();
          Log::info('product delete successed');

          $this->adminImageService->deleteImages($images);
          
        });
        
      }
      catch(\Exception $e){
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
      
      try{
        DB::transaction(function () use ($selectedItems) {
          foreach( $selectedItems as $id ){
            $images = Image::where('product_id', $id)->pluck('path')->toArray();
            $this->adminImageService->deleteImages($images);

          }
          
          Product::whereIn('id', $selectedItems)->delete();
          Log::info('product bulk delete successed');
        });
      }
      catch(\Exception $e){
        report($e);
        return false;
      }
  
      return redirect()->back()->with('success', '削除しました');
    }

    private function calculatePriceIncludingTax($priceExcludingTax, $taxRate)
    {
        return $priceExcludingTax + ($priceExcludingTax * $taxRate / 100);
    }
  
}


