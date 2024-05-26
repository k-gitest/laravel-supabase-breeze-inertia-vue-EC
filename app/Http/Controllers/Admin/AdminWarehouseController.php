<?php

namespace App\Http\Controllers\Admin;

use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use App\Http\Controllers\Controller;
use App\Models\Warehouse;

class AdminWarehouseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $result  = Warehouse::all();

        return Inertia::render('EC/Admin/WarehouseIndex', [
            'data' => $result,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        return Inertia::render('EC/Admin/WarehouseRegister');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        DB::transaction(function () use ($request){
            $request->validate([
                'name' => 'required|string|max:255|unique:warehouses,name',
                'location' => 'required|string|max:255',
            ]);
            
            $warehouse = Warehouse::create([
                'name' => $request->name,
                'location' => $request->location,                           
            ]);
        });

        return redirect()->route('admin.warehouse.index');
        
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $result = Warehouse::with(['stock.product.category'])->find($id);
        return Inertia::render('EC/Admin/WarehouseShow', [
            'data' => $result,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
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
        $warehouse = DB::transaction(function () use ($request, $id){
            $warehouse = Warehouse::find($id);
            $request->validate([
                "name" => "required|string|max:255|unique:warehouses,name,{$warehouse->id}",
                "location" => "required|string|max:255",               
            ]);

            $warehouse->name = $request->name;
            $warehouse->location = $request->location;

            if( $warehouse->isDirty() ){
                $warehouse->save();
            };
            return $warehouse;
        });

        return redirect()->route('admin.warehouse.edit', $id);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        DB::transaction(function () use ($id){
            $warehouse = Warehouse::find($id);
            $warehouse->delete();
        });

        return redirect()->route('admin.warehouse.index');
    }
}
