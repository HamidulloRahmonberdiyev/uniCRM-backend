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

        $query = Order::query()
            ->whereDate('date', $today);

        return [
            'total' => [
                'orders' => $query->count(),
                'bottles' => $query->sum('quantity')
            ],
            'done' => [
                'orders' => (clone $query)->where('status', Order::DONE)->count(),
                'bottles' => (clone $query)->where('status', Order::DONE)->sum('quantity')
            ],
            'active' => [
                'orders' => (clone $query)->where('status', Order::ACTIVE)->count(),
                'bottles' => (clone $query)->where('status', Order::ACTIVE)->sum('quantity')
            ],
            'canceled' => [
                'orders' => (clone $query)->where('status', Order::CANCEL)->count(),
                'bottles' => (clone $query)->where('status', Order::CANCEL)->sum('quantity')
            ]
        ];
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
