<?php

namespace App\Http\Controllers;

use Inertia\inertia;
use Inertia\Response;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Product;
use App\Models\Category;
use App\Models\Cart;
use App\Models\Image;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): Response
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
    public function show(Product $product, $id): Response | RedirectResponse
    {
        //
      $auth_check = Auth::check();
      $isInCart = false;
      if($auth_check){
        $user_id = auth()->user()->id;
        $cart = Cart::where('user_id', $user_id)->where('product_id', $user_id)->first();
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
