<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Order\StoreOrderRequest;
use App\Http\Requests\Order\UpdateOrderRequest;
use App\Http\Resources\OrderResource;
use App\Models\Order;
use App\Services\Order\OrderService;
use App\Traits\ApiJsonResponceTrait;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    use ApiJsonResponceTrait;

    protected OrderService $orderService;

    public function __construct(OrderService $orderService)
    {
        $this->orderService = $orderService;
    }

    public function index(Request $request)
    {
        $orders = $this->orderService->getAllOrders($request);
        return OrderResource::collection($orders);
    }

    public function store(StoreOrderRequest $request)
    {
        $order = $this->orderService->createOrder($request->validated());
        return new OrderResource($order);
    }

    public function show(Order $order)
    {
        return new OrderResource($order);
    }

    public function update(UpdateOrderRequest $request, Order $order)
    {
        $updatedOrder = $this->orderService->updateOrder($order, $request->validated());
        return new OrderResource($updatedOrder);
    }

    public function destroy(Order $order)
    {
        $this->orderService->deleteOrder($order);
        return $this->successResponse('Order deleted successfully', 200);
    }

    public function changeStatus(Request $request, Order $order)
    {
        dd($request->status);
        $request->validate(['status' => 'required|string']);

        $this->orderService->changeStatusOrder($order, $request->status);
        return $this->successResponse('Order status changed successfully', 200);
    }
}
