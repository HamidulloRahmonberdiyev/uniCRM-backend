<?php

namespace App\Services\Order;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderService
{
    public function getAllOrders(Request $request)
    {
        return Order::query()
            ->when($request->has('status'), fn($q) => $q->where('status', $request->status))
            ->latest()
            ->paginate(10);
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
            'date' => $data['date'],
            'address' => $data['address'] ?? null,
            'note' => $data['note'] ?? null,
            'location' => $data['location'] ?? null,
            'status' => 1,
        ]);
    }

    public function updateOrder(Order $order, array $data): Order
    {
        $order->update($data);
        return $order;
    }

    public function deleteOrder(Order $order): void
    {
        $order->delete();
    }
}
