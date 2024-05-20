<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use App\Models\Cart;

class CartController extends Controller
{
  /**
   * Display a listing of the resource.
   */
  public function index(): Response
  {
    $id = request()->user()->id;
    $result = Cart::with(['product.image'])->where('user_id', $id)->get();

    $total_price_excluding_tax = $result->sum(function ($item) {
      return $item->quantity * $item->product->price_excluding_tax;
    });

    $total_price_including_tax = $result->sum(function ($item) {
      return $item->quantity * $item->product->price_including_tax;
    });
    $totalPrice = [
      "total_price_excluding_tax" => $total_price_excluding_tax,
      "total_price_including_tax" => $total_price_including_tax,
    ];

    return Inertia::render('EC/CartIndex', [
      "data" => $result,
      "totalPrice" => $totalPrice,
    ]);
  }

  /**
   * Store a newly created resource in storage.
   */
  public function store(Request $request): RedirectResponse
  {
    DB::transaction(function () use ($request)
    {
      $validated = $request->validate([
         'user_id' => 'required',
         'product_id' => 'required',
         'quantity' => 'required',
      ]);

      $result = Cart::create([
        'user_id' => $request->user_id,
        'product_id' => $request->product_id,
        'quantity' => $request->quantity,
      ]);
    });

    return redirect()->route('product.show', $request->product_id);
  }

  /**
   * Show the form for editing the specified resource.
   */
  public function edit($id): Response
  {
    $result = Cart::with(['product.image'])->find($id);
    
    return Inertia::render('EC/CartEdit', [
      "data" => $result,
    ]);
  }

  /**
   * Update the specified resource in storage.
   */
  public function update(Request $request, string $id): RedirectResponse
  {
    DB::transaction(function () use ($request, $id)
    {
      $request->validate([
        "quantity" => "required|numeric",
      ]);

      $cart = Cart::find($id);
      $cart->quantity = $request->quantity;
      if ($cart->isDirty()){
        $cart->save();
      };
    });
    
    return redirect()->route('cart.edit', $id)->with('success', '更新しました');
  }

  /**
   * Remove the specified resource from storage.
   */
  public function destroy(string $id): RedirectResponse
  {
    DB::transaction(function () use ($id)
    {
      $result = Cart::find($id);
      $result->delete();
    });
    
    return redirect()->route('cart.edit')->with('success', '削除しました');
  }
}
