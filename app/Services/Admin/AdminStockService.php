<?php

namespace App\Services\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Stock;
use App\Models\Product;
use App\Models\Warehouse;
use Log;

class AdminStockService
{
    public function createStock(Request $request)
    {
        return DB::transaction(function () use ($request) {
            return Stock::create($request->validated());
        });
    }

    public function getProductWithStockAndWarehouse(string $id)
    {
        $result = Product::with(['stock.warehouse', 'image'])->findOrFail($id);
        $warehouse = Warehouse::all();

        return compact('result', 'warehouse');
    }

    public function updateStock(Request $request)
    {
        return DB::transaction(function () use ($request) {
            $stock = Stock::lockForUpdate()->findOrFail($request->id);
            $stock->fill($request->validated());

            if ($stock->isDirty()) {
                $stock->save();
            }
        });
    }

    public function deleteStock(Request $request)
    {
        return DB::transaction(function () use ($request) {
            $stock = Stock::lockForUpdate()->findOrFail($request->id);
            $stock->delete();
        });
    }
}
