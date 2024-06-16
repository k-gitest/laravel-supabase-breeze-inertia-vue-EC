<?php

namespace App\Http\Controllers\Admin;

use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use App\Http\Controllers\Controller;
use App\Models\Warehouse;
use App\Models\Product;

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
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:warehouses,name',
            'location' => 'required|string|max:255',
        ]);

        try{
            DB::transaction(function () use ($request){
                $warehouse = Warehouse::create([
                    'name' => $request->name,
                    'location' => $request->location,                           
                ]);
            });
            Log::info('Warehouse create succeeded');
        }
        catch(\Exception $e){
            Log::error('Failed to create Warehouse.', ['error' => $e->getMessage()]);
            return redirect()->back()->withErrors(['error' => 'Failed to create warehouse. Please try again.']);
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
    public function edit(string $id): Response
    {
        $result = Warehouse::find($id);
        
        return Inertia::render('EC/Admin/WarehouseEdit', [
            'data' => $result,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id): RedirectResponse
    {
        $request->validate([
            "name" => "required|string|max:255|unique:warehouses,name,{$warehouse->id}",
            "location" => "required|string|max:255",               
        ]);

        $warehouse = Warehouse::find($id);
        $warehouse->name = $request->name;
        $warehouse->location = $request->location;

        try{
            DB::transaction(function () use ($warehouse){
                if( $warehouse->isDirty() ){
                    $warehouse->save();
                };
            });
            Log::info('Warehouse update succeeded');
        }
        catch(\Exception $e){
            Log::error( 'Failed to update Warehouse.', ['error' => $e->getMessage()]);
            return redirect()->back()->withErrors(['error' => 'Failed to update warehouse. Please try again.']);
        }

        return redirect()->route('admin.warehouse.edit', $id);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id): RedirectResponse
    {
        $warehouse = Warehouse::find($id);
        try{
            DB::transaction(function () use ($warehouse){
                $warehouse->delete();
            });
            Log::info('Warehouse delete succeeded');
        }
        catch(\Exception $e){
            Log::error('Failed to delete Warehouse.', ['error' => $e->getMessage()]);
            return redirect()->back()->withErrors(['error' => 'Failed to delete warehouse. Please try again.']);
        }

        return redirect()->route('admin.warehouse.index');
    }
}
