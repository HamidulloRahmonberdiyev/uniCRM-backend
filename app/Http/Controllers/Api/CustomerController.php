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
}
