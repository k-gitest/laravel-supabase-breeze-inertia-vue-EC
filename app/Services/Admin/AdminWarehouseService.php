<?php

namespace App\Services\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Warehouse;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Log;

class AdminWarehouseService
{
    public function getAllWarehouses()
    {
        return Warehouse::all();
    }

    public function createWarehouse(Request $request)
    {
        return DB::transaction(function () use ($request) {
            return Warehouse::create($request->validated());
        });
    }

    public function getWarehouseWithProducts(string $id)
    {
        $warehouse = Warehouse::findOrFail($id);
        $result = $warehouse->product()
            ->with(['category', 'stock', 'image'])
            ->withSum(['stock' => function ($query) use ($id) {
                if ($id) {
                    $query->where('warehouse_id', $id);
                }
            }], 'quantity')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return $result;
    }

    public function getWarehouseById(string $id)
    {
        return Warehouse::findOrFail($id);
    }

    public function updateWarehouse(Request $request, string $id)
    {
        return DB::transaction(function () use ($request, $id) {
            $warehouse = Warehouse::lockForUpdate()->findOrFail($id);
            $warehouse->fill($request->validated());

            if ($warehouse->isDirty()) {
                $warehouse->save();
            }
        });
    }

    public function deleteWarehouse(Request $request)
    {
        return DB::transaction(function () use ($request) {
            $warehouse = Warehouse::lockForUpdate()->findOrFail($request->id);
            $warehouse->delete();
        });
    }
}
