<?php

namespace App\Services\Customer;

use App\Models\Customer;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CustomerService
{
    public function getAllCustomers(Request $request)
    {
        $customers = Customer::paginate($request->input('per_page', 20));

        return $customers;
    }

    public function createCustomer(array $data): Customer
    {
        return DB::transaction(function () use ($data) {
            $customer = Customer::create([
                'user_id'      => Auth::id(),
                'company_id'   => 1,
                'first_name'   => $data['first_name'],
                'last_name'    => $data['last_name'] ?? null,
                'middle_name'  => $data['middle_name'] ?? null,
                'date_of_birth' => $data['date_of_birth'] ?? null,
                'phone'        => $data['phone'],
                'phone2'       => $data['phone2'] ?? null,
                'status'       => Customer::ACTIVE,
            ]);

            if (!empty($data['customer_detail'])) {
                $customer->customerDetail()->create([
                    'region_id'      => $data['customer_detail']['region_id'] ?? null,
                    'city_id'        => $data['customer_detail']['city_id'] ?? null,
                    'district_id'    => $data['customer_detail']['district_id'] ?? null,
                    'neighborhood_id' => $data['customer_detail']['neighborhood_id'] ?? null,
                    'home'           => $data['customer_detail']['home'] ?? null,
                ]);
            }

            return $customer;
        });
    }

    public function updateCustomer(Order $order, array $data): Order
    {
        $order->update([]);

        return $order;
    }

    public function deleteCustomer(Customer $customer): void
    {
        $customer->delete();
    }
}
