<?php

namespace App\Http\Controllers\Admin;

use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\Cart;
use Log;

class AdminCartController extends Controller
{
  /**
   * Display a listing of the resource.
   */
  public function index(): Response
  {
    $result = Cart::with('product.image')->get();
    return Inertia::render('EC/Admin/CartIndex', [
      'data' => $result,
    ]);
  }

  /**
   * Store a newly created resource in storage.
   */
  public function store(Request $request): RedirectResponse
  {
      $request->validate([
         'user_id' => 'required|integer|exists:users,id',
         'product_id' => 'required|integer|exists:products,id',
         'quantity' => 'required|integer|min:1|max:99',
      ]);

    try{
      DB::transaction(function () use ($request) {
        $result = Cart::create([
          'user_id' => $request->user_id,
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
    
    return redirect()->route('admin.product.show', $request->product_id);
  }

  /**
   * Show the form for editing the specified resource.
   */
  public function edit(string $id): Response
  {
    $result = Cart::with('product.image')->findOrFail($id);
    return Inertia::render('EC/Admin/CartEdit', [
      'data' => $result,
    ]);
  }

  /**
   * Update the specified resource in storage.
   */
  public function update(Request $request, string $id): RedirectResponse
  {
    $request->validate([
      'quantity' => 'required|integer|between:1,99',
    ]);

    $result = Cart::findOrFail($id);
    $result->quantity = $request->quantity;

    try{
      DB::transaction(function () use ($result) {
        if ($result->isDirty()) {
          $result->save();
        }
      });
      Log::info('cart update succeeded');
    }
    catch(\Exception $e){
      report($e);
      return false;
    }

    return redirect()->route('admin.cart.edit', $id)->with('success', 'カート内容を変更しました');
  }

  /**
   * Remove the specified resource from storage.
   */
  public function destroy(string $id): RedirectResponse
  {
    $result = Cart::findOrFail($id);
    
    try{
      DB::transaction(function () use ($result) {
        $result->delete();
      });
      Log::info('cart delete succeeded');
    }
    catch(\Exception $e){
      report($e);
      return false;
    }

    return redirect()->route('admin.cart.edit')->with('success', '削除しました');
  }
}
