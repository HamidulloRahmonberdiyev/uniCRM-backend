<?php

namespace App\Services\Customer;

use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CustomerService
{
    public function getAllCustomers(Request $request)
    {
        $request->validate([
            'days_number' => 'nullable|integer',
        ]);

        $daysNumber = $request->days_number;

        $query = Customer::select('customers.*')
            ->addSelect(DB::raw('COALESCE(DATEDIFF(CURRENT_DATE, MAX(orders.date)), DATEDIFF(CURRENT_DATE, customers.created_at)) as days_since_last_order'))
            ->leftJoin('orders', 'customers.id', '=', 'orders.customer_id')
            ->groupBy('customers.id')
            ->orderBy('days_since_last_order', 'asc');

        if ($daysNumber !== null) {
            $query->havingRaw('days_since_last_order <= ?', [$daysNumber]);
        } else {
            $query->orderBy('customers.created_at', 'desc');
        }

        $customers = $query->paginate(20);

        $customers->each(function ($customer) {
            if ($customer->days_since_last_order <= 7 && $customer->days_since_last_order !== 0) {
                $customer->customer_status = 'ACTIVE';
            } elseif ($customer->days_since_last_order <= 10 && $customer->days_since_last_order !== 0) {
                $customer->customer_status = 'NORMAL';
            } elseif ($customer->days_since_last_order > 10) {
                $customer->customer_status = 'PASSIVE';
            } else {
                if ($customer->days_since_last_order > 10) {
                    $customer->customer_status = 'PASSIVE';
                } elseif ($customer->days_since_last_order > 7) {
                    $customer->customer_status = 'NORMAL';
                } else {
                    $customer->customer_status = 'ACTIVE';
                }
            }
        });

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

    public function updateCustomer(Customer $customer, array $data): Customer
    {
        return DB::transaction(function () use ($customer, $data) {
            $customer->update([
                'first_name'   => $data['first_name'] ?? $customer->first_name,
                'last_name'    => $data['last_name'] ?? $customer->last_name,
                'middle_name'  => $data['middle_name'] ?? $customer->middle_name,
                'date_of_birth' => $data['date_of_birth'] ?? $customer->date_of_birth,
                'phone'        => $data['phone'] ?? $customer->phone,
                'phone2'       => $data['phone2'] ?? $customer->phone2,
                'status'       => $data['status'] ?? $customer->status,
            ]);

            if (!empty($data['customer_detail'])) {
                $customer->customerDetail()->updateOrCreate(
                    ['customer_id' => $customer->id],
                    [
                        'region_id'       => $data['customer_detail']['region_id'] ?? $customer->customerDetail->region_id ?? null,
                        'city_id'         => $data['customer_detail']['city_id'] ?? $customer->customerDetail->city_id ?? null,
                        'district_id'     => $data['customer_detail']['district_id'] ?? $customer->customerDetail->district_id ?? null,
                        'neighborhood_id' => $data['customer_detail']['neighborhood_id'] ?? $customer->customerDetail->neighborhood_id ?? null,
                        'home'            => $data['customer_detail']['home'] ?? $customer->customerDetail->home ?? null,
                    ]
                );
            }

            return $customer;
        });
    }

    public function deleteCustomer(Customer $customer): void
    {
        $customer->delete();
    }
}
