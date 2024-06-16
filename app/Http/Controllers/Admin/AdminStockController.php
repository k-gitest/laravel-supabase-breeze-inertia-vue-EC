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
use Log;

class AdminStockController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'product_id' => 'required',
            'warehouse_id' => 'required',
            'quantity' => 'required',
            'reserved_quantity' => 'required',
        ]);

        try{
            $stock = DB::transaction(function () use ($request){
                return $stock = Stock::create([
                    'product_id' => $request->product_id,
                    'warehouse_id' => $request->warehouse_id,
                    'quantity' => $request->quantity,
                    'reserved_quantity' => $request->reserved_quantity,
                ]);
            });
            Log::info('Stock create succeeded');
        }
        catch(\Exception $e){
            Log::error('Failed to create stock.', ['error' => $e->getMessage()]);
            return redirect()->back()->withErrors(['error' => 'Failed to create stock. Please try again.']);
        }

        return redirect()->route('admin.product.index')->with('success', '在庫登録が成功しました');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id): Response
    {
        $result = Product::with(['stock.warehouse', 'image'])->find($id);
        $warehouse = Warehouse::all();
        
        return inertia::render('EC/Admin/StockShow',[
            'data' => $result,
            'warehouse' => $warehouse,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request): RedirectResponse
    {
        $request->validate([
           "quantity" => "required",
           "reserved_quantity" => "required",
        ]);

        try{
            DB::transaction(function () use ($request){
                $stock = Stock::find($request->id);
                $stock->quantity = $request->quantity;
                $stock->reserved_quantity = $request->reserved_quantity;
                if($stock->isDirty()){
                    $stock->save();
                }
            });
            Log::info('Stock update succeeded');
        }
        catch(\Exception $e){
            Log::error('Failed to update stock.', ['error' => $e->getMessage()]);
            return redirect()->back()->withErrors(['error' => 'Failed to update stock. Please try again.']);
        }
        
        return redirect()->route('admin.stock.index')->with('success', '在庫情報を更新しました');
        
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validate([
           "id" => "required",                
        ]);

        try{
            DB::transaction(function () use ($request){
                $stock = Stock::find($request->id);
                $stock->delete();
            });
            Log::info('Stock delete succeeded');
        }
        catch(\Exception $e){
            Log::error('Failed to delete stock.', ['error' => $e->getMessage()]);
            return redirect()->back()->withErrors(['error' => 'Failed to delete stock. Please try again.']);
        }

        return redirect()->route('admin.stock.index')->with('success', '在庫情報を削除しました');
        
    }
}
