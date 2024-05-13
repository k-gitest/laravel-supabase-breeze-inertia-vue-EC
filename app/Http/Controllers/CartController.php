<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cart;
use Inertia\Inertia;
use Illuminate\Support\Facades\DB;

class CartController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
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

        return redirect()->route('product.show', $request->product_id);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit()
    {
        //
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
        
        return Inertia::render('EC/CartEdit', [
            "data" => $result,
            "totalPrice" => $totalPrice,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        $result = Cart::find($id);
        $result->delete();
        return redirect()->route('cart.edit')->with('success', '削除しました');
    }
}
