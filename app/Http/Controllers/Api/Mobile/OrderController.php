<?php

namespace App\Http\Controllers\Api\Mobile;

use App\Http\Controllers\Controller;
use App\Http\Resources\Orders\OrderDetailResource;
use App\Models\Order;
use App\Services\Mobile\OrderService as MobileOrderService;
use App\Traits\ApiJsonResponceTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    use ApiJsonResponceTrait;

    protected MobileOrderService $mobileOrderService;

    public function __construct(MobileOrderService $mobileOrderService)
    {
        $this->mobileOrderService = $mobileOrderService;
    }

    public function activeOrders(Request $request)
    {
        $validated = $request->validate([
            'name_or_phone' => 'nullable|string|max:255',
        ]);

        $orders = $this->mobileOrderService->getActiveOrders($validated);

        return OrderDetailResource::collection($orders);
    }

    public function deliveryStats()
    {
        $activesCount = Order::where('status', Order::ACTIVE)->count();

        $deliveredCount = Order::where('supplier_id', Auth::id())->where('status', Order::DONE)->count();

        return [
            'active_orders' => $activesCount,
            'delivered_orders' => $deliveredCount,
        ];
    }
}
