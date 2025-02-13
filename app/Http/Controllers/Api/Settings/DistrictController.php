<?php

namespace App\Http\Controllers\Api\Settings;

use App\Http\Controllers\Controller;
use App\Http\Resources\DistrictDetailResource;
use App\Http\Resources\DistrictResource;
use App\Models\District;
use App\Traits\ApiJsonResponceTrait;
use Illuminate\Http\Request;

class DistrictController extends Controller
{
    use ApiJsonResponceTrait;

    public function index(Request $request)
    {
        $request->validate([
            'region_id' => 'nullable|integer|exists:regions,id',
        ]);

        $regionId = $request->region_id;

        $districts = District::when($regionId, fn($query) => $query->where('region_id', $regionId))->get();

        return DistrictResource::collection($districts);
    }

    public function show(District $district)
    {
        return new DistrictDetailResource($district->load('neighborhoods'));
    }

    public function change_status(Request $request, District $district)
    {
        $request->validate([
            'status' => 'required|integer'
        ]);

        $district->update(['status' => $request->status]);

        return $this->successResponse($district);
    }
}
