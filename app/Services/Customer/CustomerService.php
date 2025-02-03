<?php

namespace App\Services\Customer;

use App\Models\Customer;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CustomerService
{
    public function getAllCustomers(Request $request)
    {
        $customers = Customer::paginate($request->input('per_page', 20));

        return $customers;
    }

    public function createCustomer(array $data): Order
    {
        return Customer::create([]);
    }

    public function updateCustomer(Order $order, array $data): Order
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

    public function deleteCustomer(Order $order): void
    {
        $order->delete();
    }
}
