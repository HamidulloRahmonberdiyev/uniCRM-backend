<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Returned\StoreReturnedRequest;
use App\Http\Requests\Returned\UpdateReturnedRequest;
use App\Http\Resources\ReturnedResource;
use App\Models\Returned;
use App\Traits\ApiJsonResponceTrait;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReturnedController extends Controller
{
    use ApiJsonResponceTrait;

    public function index()
    {
        $returneds = Returned::paginate(20);

        return ReturnedResource::collection($returneds);
    }

    public function store(StoreReturnedRequest $request)
    {
        try {
            $returned = Returned::create([
                'user_id' => Auth::id(),
                'customer_id' => $request->customer_id,
                'quantity' => $request->quantity,
                'date' => Carbon::today(),
            ]);

            return $this->successResponse($returned, 201);
        } catch (\Exception $e) {
            return $this->errorResponse('Failed to create returned', 500);
        }
    }

    public function update(UpdateReturnedRequest $request, Returned $returned)
    {
        try {
            $returned->update([
                'quantity' => $request->quantity,
            ]);
            return $this->successResponse($returned, 201);
        } catch (\Exception $e) {
            return $this->errorResponse('Failed to create returned', 500);
        }
    }

    public function destroy(Returned $returned)
    {
        $returned->delete();

        return $this->successResponse('returned deleted successfully', 200);
    }
}
