<?php

namespace App\Http\Controllers\Admin;

use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\AdminWarehouseRequest;
use App\Models\Warehouse;
use App\Models\Product;
use Log;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class AdminWarehouseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): Response
    {
        $result  = Warehouse::all();

        return Inertia::render('EC/Admin/WarehouseIndex', [
            'data' => $result,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): Response
    {
        return Inertia::render('EC/Admin/WarehouseRegister');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(AdminWarehouseRequest $request): RedirectResponse
    {
        try{
            DB::transaction(function () use ($request){
                $warehouse = Warehouse::create($request->validated());
            });
            Log::info('Warehouse create succeeded');
        }
        catch(\Exception $e){
            report($e);
            return false;
        }

        return redirect()->route('admin.warehouse.index');
        
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id): Response
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
        
        return Inertia::render('EC/Admin/WarehouseShow', [
            'pagedata' => $result,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id): Response | RedirectResponse
    {
        $result = Warehouse::findOrFail($id);
        
        return Inertia::render('EC/Admin/WarehouseEdit', [
            'data' => $result,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(AdminWarehouseRequest $request, string $id): RedirectResponse | bool
    {
        $warehouse = Warehouse::findOrFail($id);
        $warehouse->fill($request->validated());

        try{
            throw new \Exception("エラーだよ");
            DB::transaction(function () use ($warehouse){
                if( $warehouse->isDirty() ){
                    $warehouse->save();
                };
            });
            Log::info('Warehouse update succeeded');
        }
        catch(\Exception $e){
            report($e);
            return false;
        }

        return redirect()->route('admin.warehouse.edit', $id);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id): RedirectResponse
    {
        $warehouse = Warehouse::findOrFail($id);
        try{
            DB::transaction(function () use ($warehouse){
                $warehouse->delete();
            });
            Log::info('Warehouse delete succeeded');
        }
        catch(\Exception $e){
            report($e);
            return false;
        }

        return redirect()->route('admin.warehouse.index');
    }
}
