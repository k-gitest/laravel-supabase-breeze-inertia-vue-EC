<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Product;
use App\Models\Category;
use App\Models\Cart;
use App\Models\Image;
use App\Models\Comment;
use App\Models\Favorite;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): Response
    { 
      $data = Product::with(['category', 'image', 'favorite', 'stock'])->orderBy('created_at', 'desc')->withSum('stock', 'quantity');

      $result = $data->paginate(12);

      $search_price_ranges = config('constants.PRICE_RANGES');
      
      return inertia::render('EC/ProductAllList',[
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
    public function show(Product $product, $id): Response | RedirectResponse
    {
      $auth_check = Auth::check();
      $isInCart = false;
      $isInComment = false;
      $isInFavorite = false;
      
      if($auth_check){
        $user_id = auth()->user()->id;
        $cart = Cart::where('user_id', $user_id)->where('product_id', $id)->first();
        $isInCart = $cart ? true : false;

        $comment = Comment::where('user_id', $user_id)->where('product_id', $id)->first();
        $isInComment = $comment ? true : false;

        $favorite = Favorite::where('user_id', $user_id)->where('product_id', $id)->first();
        $isInFavorite = $favorite ? true : false;
      }

      $data = Product::with(['category', 'image', 'comment', 'stock', 'favorite'])->withSum('stock', 'quantity')->find($id);

      if($data){
        return inertia::render('EC/ProductDetail',[
            "data" => $data,
            "isInCart" => $isInCart,
            "isInComment" => $isInComment,
            "isInFavorite" => $isInFavorite,
        ]);
      } else {
        return redirect("/");
      }
      
    }
}
