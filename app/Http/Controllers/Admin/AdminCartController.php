<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Cart;
use Inertia\Inertia;
use Illuminate\Support\Facades\DB;

class AdminCartController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $result = Cart::with('product.image')->get();
        return Inertia::render('EC/Admin/CartIndex', [
            'data' => $result,
        ]);
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
           'user_id' => 'required|integer|exists:users,id',
           'product_id' => 'required|integer|exists:products,id',
           'quantity' => 'required|integer|min:1|max:99',
        ]);

        $result = Cart::create([
            'user_id' => $request->user_id,
            'product_id' => $request->product_id,
            'quantity' => $request->quantity,
        ]);

        return redirect()->route('admin.product.show', $request->product_id);
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
    public function edit(string $id)
    {
        $result = Cart::with('product.image')->find($id);
        return Inertia::render('EC/Admin/CartEdit', [
            'data' => $result,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'quantity' => 'required|integer|between:1,99',
        ]);

        $result = Cart::find($id);
        $result->quantity = $request->quantity;
        
        if ($result->isDirty()) {
            $result->save();
        }

        return redirect()->route('admin.cart.edit', $id)->with('success', 'カート内容を変更しました');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $result = Cart::find($id);
        $result->delete();
        return redirect()->route('admin.cart.edit')->with('success', '削除しました');
    }
}
