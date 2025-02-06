<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CustomerTypeResource;
use App\Models\CustomerType;
use App\Traits\ApiJsonResponceTrait;
use Illuminate\Http\Request;

class CustomerTypeController extends Controller
{
    use ApiJsonResponceTrait;

    public function index()
    {
        $customerTypes = CustomerType::query()->get();

        return CustomerTypeResource::collection($customerTypes);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'label' => 'required|string',
            'number' => 'required|integer',
        ]);

        try {
            $customerType = CustomerType::create($validatedData);
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
