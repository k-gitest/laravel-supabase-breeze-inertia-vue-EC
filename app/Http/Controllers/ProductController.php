<?php

namespace App\Http\Controllers;

use Inertia\inertia;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Models\Category;
use Inertia\Response;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use App\Models\Image;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
      $data = Product::with(['category', 'image'])->get();
      return inertia::render('EC/ProductAllList',[
        "data" => $data,
      ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): Response
    {
        //
      $data = Category::all();
      return Inertia::render('EC/ProductRegister',[
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
      
      return redirect()->route('product.create', $result);
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product, $id)
    {
      $data = Product::with(['category', 'image'])->find($id);
      if($data){
        return inertia::render('EC/ProductDetail',[
            "data" => $data,
        ]);
      } else {
        return redirect("/");
      }
      
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product, $id)
    {
        //
      $data = Product::with('category')->find($id);
      return inertia::render('EC/ProductEdit',[
          "data" => $data,
      ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product, $id)
    {
        //
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

      return redirect()->route('product.edit', $product)->with('success', '更新しました');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product, $id)
    {
        //
      $product = Product::find($id);
      $product->delete();
      return redirect('/product')->with('success', '削除しました');
    }
}
