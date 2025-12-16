<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use App\Models\Cart;
use App\Services\CartService;
use App\Http\Requests\CartRequest;
use Log;

class CartController extends Controller
{
  protected $cartService;

  public function __construct(CartService $cartService)
  {
      $this->cartService = $cartService;
  }
  
  /**
   * Display a listing of the resource.
   */
  public function index(CartService $cartService): Response
  {
    $result = $this->cartService->getCartItems();
    $totalPrice = $this->cartService->getTotalPrices($result);

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
    try {
        $this->cartService->createCart($request->validated());
    } catch (\Exception $e) {
        report($e);
        return false;
    }

    return redirect()->back()->with('success', '商品をカートに追加しました。');
  }

  /**
   * Show the form for editing the specified resource.
   */
  public function edit($id): Response
  {
    $result = $this->cartService->getCartItem($id);
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
    Gate::authorize('update', $this->cartService->getCartItem($id));

    try {
        $this->cartService->updateCart($request->validated(), $id);
    } catch (\Exception $e) {
        report($e);
        return false;
    }

    return redirect()->back()->with('success', '更新しました');
  }

  /**
   * Remove the specified resource from storage.
   */
  public function destroy(string $id): RedirectResponse
  {
    Gate::authorize('isGeneral');
    Gate::authorize('delete', $this->cartService->getCartItem($id));

    try {
        $this->cartService->deleteCart($id);
    } catch (\Exception $e) {
        report($e);
        return false;
    }

    return redirect()->route('cart.index')->with('success', '削除しました');
  }
}
