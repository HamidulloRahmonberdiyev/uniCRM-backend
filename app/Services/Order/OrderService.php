<?php

namespace App\Services\Order;

use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderService
{
    public function getAllOrders(Request $request)
    {
        $request->validate([
            'name' => 'string|max:255',
            'phone' => 'string|max:15',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
        ]);

        return Order::query()
            ->select(['id', 'customer_id', 'user_id', 'company_id', 'date', 'status', 'sum'])
            ->when($request->filled('status'), fn($q) => $q->where('status', $request->status))
            ->when($request->filled('start_date') && $request->filled('end_date'), function ($q) use ($request) {
                $q->whereBetween('date', [$request->start_date, $request->end_date]);
            })
            ->when($request->filled('search'), function ($q) use ($request) {
                $search = "%{$request->search}%";
                $q->whereIn('customer_id', function ($subQuery) use ($search) {
                    $subQuery->select('id')
                        ->from('customers')
                        ->where('first_name', 'like', $search)
                        ->orWhere('last_name', 'like', $search)
                        ->orWhere('middle_name', 'like', $search)
                        ->orWhere('phone', 'like', $search)
                        ->orWhere('phone2', 'like', $search);
                });
            })
            ->with(['customer:id,first_name,last_name'])
            ->orderByDesc('created_at')
            ->paginate(20);
    }

    public function createOrder(array $data): Order
    {
        return Order::create([
            'customer_id' => $data['customer_id'],
            'user_id' => Auth::id(),
            'company_id' => 1,
            'city_id' => $data['city_id'] ?? null,
            'district_id' => $data['district_id'] ?? null,
            'neighborhood_id' => $data['neighborhood_id'] ?? null,
            'quantity' => $data['quantity'],
            'sum' => $data['sum'] ?? null,
            'date' => Carbon::today(),
            'address' => $data['address'] ?? null,
            'note' => $data['note'] ?? null,
            'location' => $data['location'] ?? null,
            'status' => Order::ACTIVE,
        ]);
    }

    public function updateOrder(Order $order, array $data): Order
    {
        $order->update([
            'customer_id' => $data['customer_id'],
            'city_id' => $data['city_id'] ?? null,
            'district_id' => $data['district_id'] ?? null,
            'neighborhood_id' => $data['neighborhood_id'] ?? null,
            'quantity' => $data['quantity'],
            'sum' => $data['sum'] ?? null,
            'address' => $data['address'] ?? null,
            'note' => $data['note'] ?? null,
            'location' => $data['location'] ?? null,
            'status' => $data['status'],
        ]);

        return $order;
    }

    public function deleteOrder(Order $order): void
    {
        $order->delete();
    }

    public function changeStatusOrder(Order $order, $data)
    {
        return $order->update(['status' => $data]);
    }

    public function getOrderStats(Request $request)
    {
        $request->validate([
            'start' => 'nullable|date',
            'end' => 'nullable|date|after_or_equal:start',
        ]);

        $query = Order::query();
        if ($request->start && $request->end) {
            $query->whereBetween('date', [$request->start, $request->end]);
        }

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
}
