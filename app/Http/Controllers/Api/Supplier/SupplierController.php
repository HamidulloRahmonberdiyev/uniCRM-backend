<?php

namespace App\Http\Controllers\Api\Supplier;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Traits\ApiJsonResponceTrait;

class SupplierController extends Controller
{
    use ApiJsonResponceTrait;

    public function getSuppliers()
    {
        $suppliers = User::supplier()->get();

        return $this->successResponse(UserResource::collection($suppliers));
    }
}
