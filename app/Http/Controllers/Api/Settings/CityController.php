<?php

namespace App\Http\Controllers\Api\Settings;

use App\Http\Controllers\Controller;
use App\Http\Requests\Cities\StoreCityRequest;
use App\Http\Requests\Cities\UpdateCityRequest;
use App\Http\Resources\CityResource;
use App\Models\City;
use App\Traits\ApiJsonResponceTrait;
use Illuminate\Http\Request;

class CityController extends Controller
{
    use ApiJsonResponceTrait;

    public function index(Request $request)
    {
        $request->validate([
            'region_id' => 'nullable|integer|exists:regions,id'
        ]);

        $regionId = $request->region_id;

        $cities = City::when($regionId, function ($query) use ($regionId) {
            $query->where('region_id', $regionId);
        })->with('region')->paginate(10);

        return CityResource::collection($cities);
    }

    public function store(StoreCityRequest $request)
    {
        $city = City::create($request->validated());
        return new CityResource($city);
    }

    public function show(City $city)
    {
        return new CityResource($city->load('region'));
    }

    public function update(UpdateCityRequest $request, City $city)
    {
        $city->update($request->validated());
        return new CityResource($city);
    }

    public function destroy(City $city)
    {
        $city->delete();
        return $this->successResponse('City deleted successfully');
    }
}
