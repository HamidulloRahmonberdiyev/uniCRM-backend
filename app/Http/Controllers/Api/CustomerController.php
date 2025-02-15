<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Customer\StoreCustomerRequest;
use App\Http\Requests\Customer\UpdateCustomerRequest;
use App\Http\Resources\CustomerResource;
use App\Http\Resources\OrderResource;
use App\Models\Customer;
use App\Services\Customer\CustomerService;
use App\Traits\ApiJsonResponceTrait;
use Carbon\Carbon;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    use ApiJsonResponceTrait;

    protected CustomerService $customerService;

    public function __construct(CustomerService $customerService)
    {
        $this->customerService = $customerService;
    }

    public function index(Request $request)
    {
        $customers = $this->customerService->getAllCustomers($request);

        return $this->successResponse([
            'data' => CustomerResource::collection($customers),
            'meta' => [
                'total_pages' => $customers->lastPage(),
                'current_page' => $customers->currentPage(),
                'total_items' => $customers->total()
            ]
        ], 'Customers retrieved successfully');
    }

    public function store(StoreCustomerRequest $request)
    {
        $customer = $this->customerService->createCustomer($request->validated());

        return new CustomerResource($customer);
    }

    public function show(Customer $customer)
    {
        return new CustomerResource($customer);
    }

    public function update(UpdateCustomerRequest $request, Customer $customer)
    {
        $customer = $this->customerService->updateCustomer($customer, $request->validated());

        return new CustomerResource($customer);
    }

    public function destroy(Customer $customer)
    {
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

        $customers = $customers->limit(10)->get()->map(function ($customer) {
            return new CustomerResource($customer);
        });

        return $this->successResponse($customers);
    }

    public function order_history(Request $request, Customer $customer)
    {
        $validated = $request->validate([
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
        ]);

        $orders = $this->customerService->getOrderHistory($customer, $validated);

        return OrderResource::collection($orders);
    }

    public function stats()
    {
        $stats = $this->customerService->getCustomerStats();
        return $this->successResponse($stats);
    }

    public function searchByPhoneNumber(Request $request)
    {
        $request->validate([
            'phone' => 'required|string',
        ]);

        $sanitizedPhone = sanitizePhone($request->phone);

        $customer = Customer::where('phone', $sanitizedPhone)
            ->orWhere('phone2', $sanitizedPhone)
            ->first();

        return new CustomerResource($customer);
    }
}
