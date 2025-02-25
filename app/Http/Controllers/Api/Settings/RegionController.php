<?php

namespace App\Http\Controllers\Api\Settings;

use App\Http\Controllers\Controller;
use App\Http\Requests\Regions\FilterRegionRequest;
use App\Http\Resources\CityResource;
use App\Http\Resources\DistrictResource;
use App\Http\Resources\NeighborhoodResource;
use App\Http\Resources\RegionDetailResource;
use App\Http\Resources\RegionResource;
use App\Http\Resources\RegionsWithRelationsResource;
use App\Models\City;
use App\Models\District;
use App\Models\Neighborhood;
use App\Models\Region;
use App\Traits\ApiJsonResponceTrait;
use Illuminate\Http\Request;

class RegionController extends Controller
{
    use ApiJsonResponceTrait;

    public function index(FilterRegionRequest $request)
    {
        if ($request->has('region_id')) {
            $region = Region::where('status', Region::ACTIVE)
                ->with([
                    'cities' => function ($query) {
                        $query->where('status', City::ACTIVE)->select('id', 'name', 'region_id');
                    },
                    'districts' => function ($query) {
                        $query->where('status', District::ACTIVE)
                            ->select('id', 'name', 'region_id')
                            ->with(['neighborhoods' => function ($query) {
                                $query->where('status', Neighborhood::ACTIVE)
                                    ->select('id', 'name', 'district_id');
                            }]);
                    }
                ])
                ->select('id', 'name')
                ->find($request->region_id);

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
            $city = City::where('status', City::ACTIVE)
                ->with(['neighborhoods' => function ($query) {
                    $query->where('status', Neighborhood::ACTIVE)
                        ->select('id', 'name', 'district_id', 'city_id');
                }])
                ->select('id', 'name')
                ->find($request->city_id);

            if (!$city) {
                return response()->json(['message' => 'City not found'], 404);
            }

            $districts = District::where('status', District::ACTIVE)
                ->select('id', 'name')
                ->get();

            return response()->json([
                'city' => new CityResource($city),
                'districts' => DistrictResource::collection($districts),
                'neighborhoods' => NeighborhoodResource::collection(
                    $city->neighborhoods
                ),
            ]);
        }

        if ($request->has('district_id')) {
            $district = District::where('status', District::ACTIVE)
                ->with(['neighborhoods' => function ($query) {
                    $query->where('status', Neighborhood::ACTIVE)
                        ->select('id', 'name', 'district_id');
                }])
                ->select('id', 'name')
                ->find($request->district_id);

            if (!$district) {
                return response()->json(['message' => 'District not found'], 404);
            }

            $cities = City::where('status', City::ACTIVE)
                ->select('id', 'name')
                ->get();

            return response()->json([
                'district' => new DistrictResource($district),
                'cities' => CityResource::collection($cities),
                'neighborhoods' => NeighborhoodResource::collection($district->neighborhoods),
            ]);
        }

        $regions = Region::where('status', Region::ACTIVE)
            ->select('id', 'name')
            ->get();

        $cities = City::where('status', City::ACTIVE)
            ->select('id', 'name', 'region_id')
            ->get();

        $districts = District::where('status', District::ACTIVE)
            ->select('id', 'name', 'region_id')
            ->get();

        $neighborhoods = Neighborhood::where('status', Neighborhood::ACTIVE)
            ->select('id', 'name', 'district_id', 'city_id')
            ->get();

        return response()->json([
            'regions' => RegionResource::collection($regions),
            'cities' => CityResource::collection($cities),
            'districts' => DistrictResource::collection($districts),
            'neighborhoods' => NeighborhoodResource::collection($neighborhoods),
        ]);
    }

    public function region_details()
    {
        $regions = Region::with(['cities', 'districts'])->get();

        return RegionDetailResource::collection($regions);
    }

    public function change_status(Request $request, Region $region)
    {
        $request->validate([
            'status' => 'required|integer'
        ]);

        $region->update(['status' => $request->status]);

        return $this->successResponse($region);
    }
}
