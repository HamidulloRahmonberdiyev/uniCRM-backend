<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\OrderResource;
use App\Models\Customer;
use App\Models\Order;
use App\Traits\ApiJsonResponceTrait;
use Carbon\Carbon;

class DashboardController extends Controller
{
    use ApiJsonResponceTrait;

    public function stats()
    {
        $today = Carbon::today();

        $ordersCount = Order::whereDate('date', $today)->count();

        $deliveredOrdersCount = Order::whereDate('date', $today)->where('status', Order::DONE)->count();

        $activeOrdersCount = Order::whereDate('date', $today)->where('status', Order::ACTIVE)->count();

        $customersCount = Customer::where('status', Customer::ACTIVE)->count();

        return $this->successResponse([
            'orders_count' => $ordersCount,
            'delivered_orders_count' => $deliveredOrdersCount,
            'active_orders_count' => $activeOrdersCount,
            'customers_count' => $customersCount,
        ]);
    }

    public function orders()
    {
        $today = Carbon::today();

        $orders = Order::query()
            ->where('status', Order::ACTIVE)
            ->whereDate('date', $today)
            ->latest('created_at')
            ->paginate(20);

        return OrderResource::collection($orders);
    }
}
