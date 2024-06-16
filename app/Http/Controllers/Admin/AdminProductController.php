<?php

namespace App\Http\Controllers\Admin;

use Inertia\inertia;
use Inertia\Response;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use App\Models\Image;
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
    public function store(Request $request): RedirectResponse
    {
      $request->validate([
        "name" => "required|unique:products,name",
        "price_excluding_tax" => "required|numeric",
        "description" => "required|string|max:255",
        "tax_rate" => "required|numeric",
        "category_id" => "required|exists:categories,id",
      ]);

      $price_including_tax = $this->calculatePriceIncludingTax($request->price_excluding_tax, $request->tax_rate);

      try{
        $result = DB::transaction(function () use ($request, $price_including_tax) {
          return $result = Product::create([
            "name" => $request->name,
            "price_excluding_tax" => $request->price_excluding_tax,
            "price_including_tax" => $price_including_tax,
            "description" => $request->description,
            "tax_rate" => $request->tax_rate,
            "category_id" => $request->category_id,
          ]);
        });
        Log::info('Product created', ['name' => $request->name]);
      }
      catch(\Exeption $e){
        Log::error('Product create error', ['name' => $request->name]);
        return redirect()->back()->with('error', 'Product create error');
      }
      
      $file = $request->file('image');
      $filenames = [];
      $product_id = $result->id;

      if ($request->hasFile('image')) {
          foreach ($request->file('image') as $i => $file) {
            try{
              $imageInfo = app()->make("SbStorage")->uploadImageToProducts($file, $product_id, $i);
              Log::info('product image save success', [$imageInfo]);
            }
            catch(\Exception $e){
              Log::error('product image save error', $e->getMessage());
            }

            $filenames[] = [
              "name" => $imageInfo["Id"],
              "path" => $imageInfo["Key"],
              "product_id" => $result->id,
            ];
          }
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
        Log::error('Product image save error', $e->getMessage());
        return redirect()->back()->with('error', 'Product image save error');
      }

      return redirect()->route('admin.product.index')->with('success', 'Product created');
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product, $id): Response | RedirectResponse
    {
      $data = Product::with(['category', 'image'])->find($id);
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
      $data = Product::with('category', 'image')->find($id);
      
      return inertia::render('EC/Admin/ProductEdit',[
          "data" => $data,
      ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id): RedirectResponse
    {
      $product = Product::find($id);
      $request->validate([
         "name" => "required|unique:products,name,{$product->id}",
         "price_excluding_tax" => "required|numeric",
         "description" => "required|string|max:255",
         "tax_rate" => "required|numeric",
         "category_id" => "required|exists:categories,id",
      ]);

      $price_including_tax = $this->calculatePriceIncludingTax($request->price_excluding_tax, $request->tax_rate);

      $product->name = $request->name;
      $product->description = $request->description;
      $product->price_excluding_tax = $request->price_excluding_tax;
      $product->tax_rate = $request->tax_rate;
      $product->category_id = $request->category_id;
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
        Log::error('Failed to update product.', ['error' => $e->getMessage()]);
        return redirect()->back()->withErrors(['error' => 'Failed to update product. Please try again.']);
      }
      
      $file = $request->file('image');
      $filenames = [];
      $product_id = $product->id;

      if ($request->hasFile('image')) {
          $latestImage = Image::where('product_id', $id)->latest('created_at')->first();
          $filename = pathinfo($latestImage->path, PATHINFO_FILENAME);
          $filenameParts = explode('_', $filename);
          $latestnumber = end($filenameParts) + 1;
        
          foreach ($request->file('image') as $i => $file) {
            try{
              $imageInfo = app()->make("SbStorage")->uploadImageToProducts($file, $product_id, $i, $latestnumber);
              Log::info('Image uploaded.', ['id' => $product->id]);
            }
            catch(\Exenption $e){
              Log::error('Failed to upload image.', ['error' => $e->getMessage()]);
              return redirect()->back()->withErrors(['error' => 'Failed to upload image. Please try again.']);
            }
            
            $filenames[] = [
              "name" => $imageInfo["Id"],
              "path" => $imageInfo["Key"],
              "product_id" => $id,
            ];
          }
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
        Log::error('Failed to update product.', ['error' => $e->getMessage()]);
        return redirect()->back()->withErrors(['error' => 'Failed to update product. Please try again.']);
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
          Log::error('product image delete failed.', ['error' => $e->getMessage()]);
          return redirect()->back()->withErrors(['error' => 'Failed to delete product image. Please try again.']);
        }
      }
      
      try{
        DB::transaction(function () use ($id) {
          $product = Product::find($id);
          $product->delete();
        });
        Log::info('product delete successed');
      }
      catch(\Exception $e){
        Log::error('product delete failed.', ['error' => $e->getMessage()]);
        return redirect()->back()->withErrors(['error' => 'Failed to delete product. Please try again.']);
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
            Log::error('product image delete failed.', ['error' => $e->getMessage()]);
            return redirect()->back()->withErrors(['error' => 'Failed to delete product image. Please try again.']);
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
        Log::error('product bulk delete failed.', ['error' => $e->getMessage()]);
        return redirect()->back()->withErrors(['error' => 'Failed to delete product. Please try again.']);
      }
  
      return redirect()->back()->with('success', '削除しました');
    }

    public function calculatePriceIncludingTax($priceExcludingTax, $taxRate)
    {
        return $priceExcludingTax + ($priceExcludingTax * $taxRate / 100);
    }
  
}


