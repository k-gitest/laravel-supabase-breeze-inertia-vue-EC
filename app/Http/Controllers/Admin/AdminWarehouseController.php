<?php

namespace App\Http\Controllers\Admin;

use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\AdminWarehouseRequest;
use App\Services\Admin\AdminWarehouseService;
use App\Models\Warehouse;
use App\Models\Product;
use Log;

class AdminWarehouseController extends Controller
{
    protected $adminWarehouseService;

    public function __construct(AdminWarehouseService $adminWarehouseService)
    {
        $this->adminWarehouseService = $adminWarehouseService;
    }
    
    /**
     * Display a listing of the resource.
     */
    public function index(): Response
    {
        $result = $this->adminWarehouseService->getAllWarehouses();

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
    public function store(AdminWarehouseRequest $request): RedirectResponse|bool
    {
        try {
            $this->adminWarehouseService->createWarehouse($request);
            Log::info('Warehouse create succeeded');
        } catch (\Exception $e) {
            report($e);
            return false;
        }

        return redirect()->route('admin.warehouse.index')->with('success', '在庫登録が成功しました');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id): Response|bool
    {
        try {
            $result = $this->adminWarehouseService->getWarehouseWithProducts($id);
        } catch (\Exception $e) {
            report($e);
            return false;
        }

        return Inertia::render('EC/Admin/WarehouseShow', [
            'pagedata' => $result,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id): Response | bool
    {
        try {
            $result = $this->adminWarehouseService->getWarehouseById($id);
        } catch (ModelNotFoundException $e) {
            report($e);
            return false;
        }

        return Inertia::render('EC/Admin/WarehouseEdit', [
            'data' => $result,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(AdminWarehouseRequest $request, string $id): RedirectResponse | bool
    {
        try {
            $this->adminWarehouseService->updateWarehouse($request, $id);
            Log::info('Warehouse update succeeded');
        } catch (\Exception $e) {
            report($e);
            return false;
        }

        return redirect()->back()->with('success', '更新しました');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request): RedirectResponse|bool
    {
        $request->validate([
            "id" => "required|integer|exists:warehouses,id",
        ]);

        try {
            $this->adminWarehouseService->deleteWarehouse($request);
            Log::info('Warehouse delete succeeded');
        } catch (\Exception $e) {
            report($e);
            return false;
        }

        return redirect()->route('admin.warehouse.index')->with('success', '削除しました');
    }
}
