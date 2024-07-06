<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItem;
use App\Services\OrderService;

class OrderController extends Controller
{
    protected $orderService;

    public function __construct(OrderService $orderService)
    {
        $this->orderService = $orderService;
    }
    
    /**
     * Display a listing of the resource.
     */
    public function index(): Response
    {
        $userId = auth()->id();
        $result = $this->orderService->getOrderList($userId);

        return Inertia::render('EC/Order', [
            "pagedata" => $result,
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $userId = auth()->user()->id;
        $result = $this->orderService->getOrderDetails($id, $userId);

        return Inertia::render('EC/OrderDetail', [
            "data" => $result,
        ]);
    }
}
