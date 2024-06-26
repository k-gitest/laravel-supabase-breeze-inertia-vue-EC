<?php

namespace App\Http\Controllers\Admin;

use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\AdminStockRequest;
use App\Models\Stock;
use App\Models\Product;
use App\Models\Warehouse;
use Log;

class AdminStockController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(AdminStockRequest $request): RedirectResponse|bool
    {
        try{
            $stock = DB::transaction(function () use ($request){
                return $stock = Stock::create($request->validated());
            });
            Log::info('Stock create succeeded');
        }
        catch(\Exception $e){
            report($e);
            return false;
        }

        return redirect()->route('admin.product.index')->with('success', '在庫登録が成功しました');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id): Response
    {
        $result = Product::with(['stock.warehouse', 'image'])->findOrFail($id);
        $warehouse = Warehouse::all();
        
        return inertia::render('EC/Admin/StockShow',[
            'data' => $result,
            'warehouse' => $warehouse,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(AdminStockRequest $request): RedirectResponse|bool
    {
        try{
            DB::transaction(function () use ($request){
                $stock = Stock::lockForUpdate()->findOrFail($request->id);
                $stock->fill($request->validated());
                
                if($stock->isDirty()){
                    $stock->save();
                }
            });
            Log::info('Stock update succeeded');
        }
        catch(\Exception $e){
            report($e);
            return false;
        }
        
        return redirect()->back()->with('success', '在庫情報を更新しました');
        
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request): RedirectResponse|bool
    {
        $request->validate([
           "id" => "required|integer|exists:stocks,id",                
        ]);
        
        try{
            DB::transaction(function () use ($request){ 
                $stock = Stock::lockForUpdate()->findOrFail($request->id);
                $stock->delete();
            });
            Log::info('Stock delete succeeded');
        }
        catch(\Exception $e){
            report($e);
            return false;
        }

        return redirect()->back()->with('success', '在庫情報を削除しました');
        
    }
}
