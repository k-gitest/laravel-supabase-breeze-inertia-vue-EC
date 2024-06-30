<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use App\Models\Cart;
use App\Services\CartPriceService;
use App\Http\Requests\CartRequest;
use Log;

class CartController extends Controller
{
  /**
   * Display a listing of the resource.
   */
  public function index(CartPriceService $cartPriceService): Response
  {
    $result = Cart::with(['product.image'])->where('user_id', auth()->id())->paginate(12);

    $totalPrice = $cartPriceService->getTotalPrices($result);

    return Inertia::render('EC/CartIndex', [
      "pagedata" => $result,
      "totalPrice" => $totalPrice,
    ]);
  }

  /**
   * Store a newly created resource in storage.
   */
  public function store(CartRequest $request): RedirectResponse
  {
    $userId = auth()->id();

    try{
      DB::transaction(function () use ($request, $userId){
        $result = Cart::create([
          'user_id' => $userId,
          'product_id' => $request->product_id,
          'quantity' => $request->quantity,
        ]);
      });
      Log::info('cart create succeeded');
    }
    catch(\Exception $e){
      report($e);
      return false;
    }

    return redirect()->route('product.show', $request->product_id);
  }

  /**
   * Show the form for editing the specified resource.
   */
  public function edit($id): Response
  {
    $result = Cart::with(['product.image'])->findOrFail($id);
    
    Gate::authorize('update', $result);
    
    return Inertia::render('EC/CartEdit', [
      "data" => $result,
    ]);
  }

  /**
   * Update the specified resource in storage.
   */
  public function update(CartRequest $request, string $id): RedirectResponse
  {
    Gate::authorize('isGeneral');

    $cart = Cart::findOrFail($id);
    
    Gate::authorize('update', $cart);
    
    $cart->quantity = $request->quantity;

    try{
      DB::transaction(function () use ($cart) {
        if ($cart->isDirty()){
          $cart->save();
        };
      });
      Log::info('cart update succeeded');
    }
    catch(\Exception $e){
      report($e);
      return false;
    }
    
    return redirect()->route('cart.edit', $id)->with('success', '更新しました');
  }

  /**
   * Remove the specified resource from storage.
   */
  public function destroy(string $id): RedirectResponse
  {
    Gate::authorize('isGeneral');
    
    $result = Cart::findOrFail($id);
    
    Gate::authorize('delete', $result);
    
    try{
      DB::transaction(function () use ($result)
      {
        $result->delete();
      });
      Log::info('cart delete succeeded');
    }
    catch(\Exception $e){
      report($e);
      return false;
    }
    
    return redirect()->route('cart.index')->with('success', '削除しました');
  }
}
