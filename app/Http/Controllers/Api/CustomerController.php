<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Customer\StoreCustomerRequest;
use App\Http\Requests\Customer\UpdateCustomerRequest;
use App\Models\Customer;
use App\Traits\ApiJsonResponceTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class CustomerController extends Controller
{
    use ApiJsonResponceTrait;

    public function index(Request $request)
    {
        $customers = Customer::paginate($request->input('per_page', 20));
        return $this->successResponse($customers, 'Customers retrieved successfully');
    }

    public function store(StoreCustomerRequest $request)
    {
        $customer = Customer::create($request->validated());
        return $this->successResponse($customer, 'Customer created successfully', 201);
    }

    public function show(Customer $customer)
    {
        return $this->successResponse($customer, 'Customer details retrieved successfully');
    }

    public function update(UpdateCustomerRequest $request, Customer $customer)
    {
        $customer->update($request->validated());
        return $this->successResponse($customer, 'Customer updated successfully');
    }

    public function destroy(Customer $customer)
    {
        if (Gate::denies('delete', $customer)) {
            return $this->errorResponse('Unauthorized', 403);
        }

        $customer->delete();
        return $this->successResponse(null, 'Customer deleted successfully');
    }

    public function search(Request $request)
    {
        $nameQuery = $request->input('name');
        $phoneQuery = $request->input('phone');

        $customers = Customer::query();

        if ($nameQuery) {
            $customers->where(function ($q) use ($nameQuery) {
                $q->where('first_name', 'LIKE', "%{$nameQuery}%")
                    ->orWhere('last_name', 'LIKE', "%{$nameQuery}%")
                    ->orWhere('middle_name', 'LIKE', "%{$nameQuery}%");
            });
        }

        if ($phoneQuery) {
            $customers->where(function ($q) use ($phoneQuery) {
                $q->where('phone', 'LIKE', "%{$phoneQuery}%")
                    ->orWhere('phone2', 'LIKE', "%{$phoneQuery}%");
            });
        }

        $customers->orderByRaw(
            "(CASE 
                WHEN first_name LIKE ? THEN 5
                WHEN last_name LIKE ? THEN 4
                WHEN middle_name LIKE ? THEN 3
                WHEN phone LIKE ? THEN 2
                WHEN phone2 LIKE ? THEN 1
                ELSE 0
            END) DESC",
            [
                "{$nameQuery}%",
                "{$nameQuery}%",
                "{$nameQuery}%",
                "{$phoneQuery}%",
                "{$phoneQuery}%"
            ]
        );

        return $this->successResponse($customers->limit(10)->get());
    }
}
