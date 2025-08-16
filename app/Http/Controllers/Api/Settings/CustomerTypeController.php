<?php

namespace App\Http\Controllers\Api\Settings;

use App\Http\Controllers\Controller;
use App\Http\Requests\Settings\CustomerType\StoreCustomerTypeRequest;
use App\Http\Resources\CustomerTypeResource;
use App\Models\CustomerType;
use App\Traits\ApiJsonResponceTrait;

class CustomerTypeController extends Controller
{
    use ApiJsonResponceTrait;

    public function index()
    {
        $customerTypes = CustomerType::query()->get();

        return $this->successResponse(CustomerTypeResource::collection($customerTypes));
    }

    public function store(StoreCustomerTypeRequest $request)
    {
        try {
            $customerType = CustomerType::create($request->validated());
            return $this->successResponse(new CustomerTypeResource($customerType), 201);
        } catch (\Exception $e) {
            return $this->errorResponse('Failed to create customerType', 500);
        }
    }

    public function destroy(CustomerType $customerType)
    {
        $customerType->delete();

        return $this->successResponse('CustomerType deleted successfully', 200);
    }
}
