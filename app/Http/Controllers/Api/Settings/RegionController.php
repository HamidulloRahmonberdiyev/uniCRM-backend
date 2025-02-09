<?php

namespace App\Http\Controllers\Api\Settings;

use App\Http\Controllers\Controller;
use App\Http\Resources\CityResource;
use App\Http\Resources\DistrictResource;
use App\Http\Resources\NeighborhoodResource;
use App\Http\Resources\RegionResource;
use App\Models\City;
use App\Models\District;
use App\Models\Neighborhood;
use App\Models\Region;
use Illuminate\Http\Request;

class RegionController extends Controller
{
    public function index(Request $request)
    {
        $request->validate([
            'region_id' => 'nullable|integer',
            'district_id' => 'nullable|integer',
            'city_id' => 'nullable|integer',
        ]);

        if ($request->has('region_id')) {
            $region = Region::with(['cities', 'districts', 'districts.neighborhoods'])->find($request->region_id);
            if (!$region) {
                return response()->json(['message' => 'Region not found'], 404);
            }
            return response()->json([
                'region' => new RegionResource($region),
                'cities' => CityResource::collection($region->cities),
                'districts' => DistrictResource::collection($region->districts),
                'neighborhoods' => NeighborhoodResource::collection(
                    $region->districts->flatMap->neighborhoods
                ),
            ]);
        }

        if ($request->has('city_id')) {
            $city = City::with(['districts', 'districts.neighborhoods'])->find($request->city_id);
            if (!$city) {
                return response()->json(['message' => 'City not found'], 404);
            }
            return response()->json([
                'city' => new CityResource($city),
                'districts' => DistrictResource::collection($city->districts),
                'neighborhoods' => NeighborhoodResource::collection(
                    $city->districts->flatMap->neighborhoods
                ),
            ]);
        }

        if ($request->has('district_id')) {
            $district = District::with('neighborhoods')->find($request->district_id);
            if (!$district) {
                return response()->json(['message' => 'District not found'], 404);
            }
            return response()->json([
                'district' => new DistrictResource($district),
                'neighborhoods' => NeighborhoodResource::collection($district->neighborhoods),
            ]);
        }

        $regions = Region::where('status', Region::ACTIVE)->get();
        $cities = City::where('status', City::ACTIVE)->get();
        $districts = District::where('status', District::ACTIVE)->get();
        $neighborhoods = Neighborhood::where('status', Neighborhood::ACTIVE)->get();

        return response()->json([
            'regions' => RegionResource::collection($regions),
            'cities' => CityResource::collection($cities),
            'districts' => DistrictResource::collection($districts),
            'neighborhoods' => NeighborhoodResource::collection($neighborhoods),
        ]);
    }
}
