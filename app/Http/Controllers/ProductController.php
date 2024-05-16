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
use App\Models\Cart;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
      $data = Product::with(['category', 'image'])->orderBy('created_at', 'desc')->get();
      return inertia::render('EC/ProductAllList',[
        "data" => $data,
      ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product, $id)
    {
        //
      $user_id = Auth::user()->id;
      $isInCart = false;
      if($user_id){
        $cart = Cart::where('user_id', $user_id)->where('product_id', $id)->first();
        $isInCart = $cart ? true : false;
      }

      $data = Product::with(['category', 'image'])->find($id);

      if($data){
        return inertia::render('EC/ProductDetail',[
            "data" => $data,
            "isInCart" => $isInCart,
        ]);
      } else {
        return redirect("/");
      }
      
    }

}
