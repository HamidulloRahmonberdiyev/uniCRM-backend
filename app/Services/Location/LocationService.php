<?php

namespace App\Services\Location;

use App\Http\Resources\CityResource;
use App\Http\Resources\DistrictResource;
use App\Http\Resources\NeighborhoodResource;
use App\Http\Resources\RegionResource;
use App\Models\District;
use App\Models\Neighborhood;
use App\Models\Region;
use App\Traits\ApiJsonResponceTrait;

class LocationService
{
    use ApiJsonResponceTrait;

    public function getLocations($request)
    {
        if ($request->has('region_id')) {
            return $this->getRegionData($request->region_id);
        }

        if ($request->has('district_id')) {
            return $this->getDistrictData($request->district_id);
        }

        return $this->getAllLocations();
    }

    private function getRegionData($regionId)
    {
        $region = Region::where('status', Region::ACTIVE)
            ->with([
                'districts' => function ($query) {
                    $query->where('status', District::ACTIVE)
                        ->select('id', 'name', 'region_id')
                        ->with(['neighborhoods' => function ($query) {
                            $query->where('status', Neighborhood::ACTIVE)
                                ->whereHas('district', function ($query) {
                                    $query->where('status', District::ACTIVE);
                                })
                                ->select('id', 'name', 'district_id');
                        }]);
                }
            ])
            ->select('id', 'name')
            ->find($regionId);

        if (!$region) {
            return $this->errorResponse('Region not found', 404);
        }

        return response()->json([
            'region' => new RegionResource($region),
            'districts' => DistrictResource::collection($region->districts),
            'neighborhoods' => NeighborhoodResource::collection(
                $region->districts->flatMap->neighborhoods
            ),
        ]);
    }

    private function getDistrictData($districtId)
    {
        $district = District::where('status', District::ACTIVE)
            ->with(['neighborhoods' => function ($query) {
                $query->where('status', Neighborhood::ACTIVE)
                    ->select('id', 'name', 'district_id');
            }])
            ->select('id', 'name')
            ->find($districtId);

        if (!$district) {
            return $this->errorResponse('District not found', 404);
        }

        return response()->json([
            'district' => new DistrictResource($district),
            'neighborhoods' => NeighborhoodResource::collection($district->neighborhoods),
        ]);
    }

    private function getAllLocations()
    {
        $regions = Region::where('status', Region::ACTIVE)
            ->select('id', 'name')
            ->get();

        $districts = District::where('status', District::ACTIVE)
            ->select('id', 'name', 'region_id')
            ->get();

        $neighborhoods = Neighborhood::where('status', Neighborhood::ACTIVE)
            ->whereHas('district', function ($query) {
                $query->where('status', District::ACTIVE);
            })
            ->select('id', 'name', 'district_id')
            ->get();

        return response()->json([
            'regions' => RegionResource::collection($regions),
            'districts' => DistrictResource::collection($districts),
            'neighborhoods' => NeighborhoodResource::collection($neighborhoods),
        ]);
    }
}
