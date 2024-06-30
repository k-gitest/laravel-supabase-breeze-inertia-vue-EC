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
use App\Http\Requests\Admin\AdminProductRequest;
use Log;

class AdminProductController extends Controller
{  
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
    public function store(AdminProductRequest $request, Product $product): RedirectResponse
    {      
      $price_including_tax = $this->calculatePriceIncludingTax($request->price_excluding_tax, $request->tax_rate);
      
      $validateData = $request->validated();
      
      $validateData["price_including_tax"] = $price_including_tax;

      try{
        $result = DB::transaction(function () use ($validateData, $product) {
          return $result = $product->create($validateData);
        });
        Log::info('Product created', ['name' => $result->name]);
      }
      catch(\Exeption $e){
        report($e);
        return false;
      }
      
      $product_id = $result->id;
      
      try{
        $filenames = $this->handleImageUploads($product_id, $request);
        Log::info('product image save success');
      }
      catch(\Exception $e){
        report($e);
        return false;
      }

      $result = [];

      try{
        DB::transaction(function () use ($filenames, $result) {
          foreach ($filenames as $filename) {
              $result[] = Image::create([
                  'name' => $filename['name'],
                  'path' => $filename['path'],
                  'product_id' => $filename['product_id'],
              ]);
          }
        }, 3);
        Log::info('Product image save success', ['result' => $result]);
      }
      catch(\Exeption $e){
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
    public function update(AdminProductRequest $request, $id): RedirectResponse
    {
      $product = Product::findOrFail($id);
      $product->fill($request->validated());

      $price_including_tax = $this->calculatePriceIncludingTax($request->price_excluding_tax, $request->tax_rate);
      
      $product->price_including_tax = $price_including_tax;

      try{
        DB::transaction(function () use ($product){
          if ($product->isDirty()) {
              $product->save();
          }
        });
        Log::info('Product updated.', ['id' => $product->id]);
      }
      catch(\Exception $e){
        report($e);
        return false;
      }
      
      $product_id = $product->id;

      $latestNumber = $this->getLatestImageNumber($product_id);

      try{
        $filenames = $this->handleImageUploads($product_id, $request,  $latestNumber);
        Log::info('Image uploaded.', ['id' => $product->id]);
      }
      catch(\Exenption $e){
        report($e);
        return false;
      }

      $result = [];

      try{
        DB::transaction(function () use ($result, $filenames){
          foreach ($filenames as $filename) {
              $result[] = Image::create([
                  'name' => $filename['name'],
                  'path' => $filename['path'],
                  'product_id' => $filename['product_id'],
              ]);
          }
        });
        Log::info('Product updated.', ['id' => $product->id]);
      }
      catch(\Exception $e){
        report($e);
        return false;
      }
      
      return redirect()->route('admin.product.edit', $id)->with('success', '更新しました');
      
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product, $id): RedirectResponse
    {
      $images = Image::where('product_id', $id)->pluck('path')->toArray();
    
      if($images){
        try{
          app()->make("SbStorage")->deleteImage($images);
          Log::info('product image delete successed');
        }
        catch(\Exception $e){
          report($e);
          return false;
        }
      }

      $product = Product::findOrFail($id);
      
      try{
        DB::transaction(function () use ($id) {
          $product->delete();
        });
        Log::info('product delete successed');
      }
      catch(\Exception $e){
        report($e);
        return false;
      }
      
      return redirect('admin/product')->with('success', '削除しました');
    }

    public function bulkDestroy(Product $product, Request $request): RedirectResponse
    {      
      $request->validate([
          "ids" => "required|array",
          'ids.*' => 'integer',
      ]);
      
      $selectedItems = $request->ids;
      
      foreach( $selectedItems as $id ){
        $images = Image::where('product_id', $id)->pluck('path')->toArray();
        if($images){
          try{
            app()->make("SbStorage")->deleteImage($images);
            Log::info('product image delete successed');
          }
          catch(\Exception $e){
            report($e);
            return false;
          }
        }
      }
      
      try{
        DB::transaction(function () use ($selectedItems) {
          $result = Product::whereIn('id', $selectedItems)->delete();
        });
        Log::info('product bulk delete successed');
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

    private function getLatestImageNumber($product_id)
    {
      $latestImage = Image::where('product_id', $product_id)->latest('created_at')->orderBy('id', 'desc')->first();
      $filename = pathinfo($latestImage->path, PATHINFO_FILENAME);
      $filenameParts = explode('_', $filename);
      $latestnumber = end($filenameParts) + 1;
      return $latestnumber;
    }
  
    private function handleImageUploads($product_id, Request $request, $latestNumber=0)
    {
        $filenames = [];

        if ($request->hasFile('image')) {
            foreach ($request->file('image') as $i => $file) {
                $fileNumber = $i + $latestNumber;
                $imageInfo = app()->make("SbStorage")->uploadImageToProducts($file, $product_id, $fileNumber);
                //$imageInfo = array_change_key_case($imageInfo, CASE_LOWER);
                $filenames[] = [
                  "name" => $imageInfo["Id"],
                  "path" => $imageInfo["Key"],
                  "product_id" => $product_id,
                ];
            }
        }

        return $filenames;
    }
  
}


