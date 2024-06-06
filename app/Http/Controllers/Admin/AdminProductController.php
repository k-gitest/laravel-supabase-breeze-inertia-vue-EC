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
        //
      DB::transaction(function () use ($request) {

        $request->validate([
          "name" => "required|unique:products,name",
          "price_excluding_tax" => "required|numeric",
          "description" => "required|string|max:255",
          "tax_rate" => "required|numeric",
          "category_id" => "required|exists:categories,id",
        ]);

        $price_excluding_tax = $request->price_excluding_tax;
        $tax_rate = $request->tax_rate;
        $price_including_tax = $price_excluding_tax + ($price_excluding_tax * $tax_rate / 100);

        $result = Product::create([
          "name" => $request->name,
          "price_excluding_tax" => $request->price_excluding_tax,
          "price_including_tax" => $price_including_tax,
          "description" => $request->description,
          "tax_rate" => $request->tax_rate,
          "category_id" => $request->category_id,
        ]);

        $file = $request->file('image');
        $filenames = [];

        if ($request->hasFile('image')) {
            foreach ($request->file('image') as $i => $file) {
              $imageType = getimagesize($file)["mime"];
              $parts = explode("/", $imageType);
              $extension = end($parts);
              $path = "/product/{$result->id}/{$result->id}_{$i}.{$extension}";
              $imageInfo = app()->make("SbStorage")->uploadImage($file, $path);
              $filenames[] = [
                "name" => $imageInfo["Id"],
                "path" => $imageInfo["Key"],
                "product_id" => $result->id,
              ];
            }
        }

        $result = [];

        foreach ($filenames as $filename) {
            $result[] = Image::create([
                'name' => $filename['name'],
                'path' => $filename['path'],
                'product_id' => $filename['product_id'],
            ]);
        }

      }, 3); 

      return redirect()->route('admin.product.create');
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
      DB::transaction(function () use ($request, $id){
        $product = Product::find($id);
        $request->validate([
           "name" => "required|unique:products,name,{$product->id}",
           "price_excluding_tax" => "required|numeric",
           "description" => "required|string|max:255",
           "tax_rate" => "required|numeric",
           "category_id" => "required|exists:categories,id",
        ]);

        $price_excluding_tax = $request->price_excluding_tax;
        $tax_rate = $request->tax_rate;
        $price_including_tax = $price_excluding_tax + ($price_excluding_tax * $tax_rate / 100);

        $product->name = $request->name;
        $product->description = $request->description;
        $product->price_excluding_tax = $request->price_excluding_tax;
        $product->tax_rate = $request->tax_rate;
        $product->category_id = $request->category_id;
        $product->price_including_tax = $price_including_tax;

        if ($product->isDirty()) {
            $product->save();
        }

        $file = $request->file('image');
        $filenames = [];

        if ($request->hasFile('image')) {
            $latestImage = Image::where('product_id', $id)->latest('created_at')->first();
            $filename = pathinfo($latestImage->path, PATHINFO_FILENAME);
            $filenameParts = explode('_', $filename);
            $latestnumber = end($filenameParts) + 1;
            foreach ($request->file('image') as $i => $file) {
              $imageType = getimagesize($file)["mime"];
              $parts = explode("/", $imageType);
              $extension = end($parts);
              $fileNumber = $i + $latestnumber;
              $path = "/product/{$id}/{$id}_{$fileNumber}.{$extension}";
              $imageInfo = app()->make("SbStorage")->uploadImage($file, $path);
              $filenames[] = [
                "name" => $imageInfo["Id"],
                "path" => $imageInfo["Key"],
                "product_id" => $id,
              ];
            }
        }

        $result = [];

        foreach ($filenames as $filename) {
            $result[] = Image::create([
                'name' => $filename['name'],
                'path' => $filename['path'],
                'product_id' => $filename['product_id'],
            ]);
        }
        
      });
      
      return redirect()->route('admin.product.edit', $id)->with('success', '更新しました');
      
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product, $id): RedirectResponse
    {
      DB::transaction(function () use ($id) {
        $product = Product::find($id);
        $product->delete();
      });
      
      return redirect('admin/product')->with('success', '削除しました');
    }

    public function bulkDestroy(Product $product, Request $request): RedirectResponse
    {
      DB::transaction(function () use ($request) {
        $request->validate([
            "ids" => "required|array",
            'ids.*' => 'integer',
        ]);
        $selectedItems = $request->ids;
        $result = Product::whereIn('id', $selectedItems)->delete();
      });
  
      return redirect()->back()->with('success', '削除しました');
    }
}
