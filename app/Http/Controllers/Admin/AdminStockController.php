<?php

namespace App\Http\Controllers\Admin;

use Inertia\inertia;
use Inertia\Response;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\Stock;
use App\Models\Product;
use App\Models\Warehouse;

class AdminStockController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): Response
    {
        //
        $result = Product::with(['image', 'category'])->withSum('stock', 'quantity')->get();

        return inertia::render('EC/Admin/StockIndex', [
            'data' => $result,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): Response
    {
        $product = Product::all();
        $warehouse = Warehouse::all();
        return inertia::render('EC/Admin/StockRegister', [
            "data" => [
                "product" => $product,
                "warehouse" => $warehouse,
            ],
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $stock = DB::transaction(function () use ($request){
           $request->validate([
                'product_id' => 'required',
                'warehouse_id' => 'required',
                'quantity' => 'required',
                'reserved_quantity' => 'required',
           ]);

            return $stock = Stock::create([
                'product_id' => $request->product_id,
                'warehouse_id' => $request->warehouse_id,
                'quantity' => $request->quantity,
                'reserved_quantity' => $request->reserved_quantity,
            ]);
        });

        return redirect()->route('admin.stock.index')->with('success', '在庫登録が成功しました');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
        $result = Product::with(['stock.warehouse'])->find($id);
        $warehouse = Warehouse::all();
        return inertia::render('EC/Admin/StockShow',[
            'data' => $result,
            'warehouse' => $warehouse,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
        $result = Stock::with(['product.image', 'product.category', 'warehouse'])->find($id);
        return inertia::render('EC/Admin/StockEdit',[
            'data' => $result,                    
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        DB::transaction(function () use ($request){
            $request->validate([
               "quantity" => "required",
               "reserved_quantity" => "required",
            ]);

            $stock = Stock::find($request->id);
            $stock->quantity = $request->quantity;
            $stock->reserved_quantity = $request->reserved_quantity;
            if($stock->isDirty()){
                $stock->save();
            }
        });
        
        return redirect()->route('admin.stock.index')->with('success', '在庫情報を更新しました');
        
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        DB::transaction(function () use ($request){
            $request->validate([
               "id" => "required",                
            ]);

            $stock = Stock::find($request->id);
            $stock->delete();
        });

        return redirect()->route('admin.stock.index')->with('success', '在庫情報を削除しました');
        
    }
}
