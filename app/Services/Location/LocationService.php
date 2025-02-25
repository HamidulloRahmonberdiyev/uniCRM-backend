<?php

namespace App\Services\Location;

use App\Http\Resources\CityResource;
use App\Http\Resources\DistrictResource;
use App\Http\Resources\NeighborhoodResource;
use App\Http\Resources\RegionResource;
use App\Models\City;
use App\Models\District;
use App\Models\Neighborhood;
use App\Models\Region;

class LocationService
{
    public function getLocationData(array $data)
    {
        if ($data['region_id']) {
            return $this->getDataByRegion($data['region_id']);
        }

        if ($data['city_id']) {
            return $this->getDataByCity($data['city_id']);
        }

        if ($data['district_id']) {
            return $this->getDataByDistrict($data['district_id']);
        }

        return $this->getAllLocationData();
    }

    private function getDataByRegion(int $regionId): array
    {
        $region = Region::where('status', Region::ACTIVE)
            ->with([
                'cities' => function ($query) {
                    $query->where('status', City::ACTIVE)
                        ->select('id', 'name', 'region_id');
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
            ->find($regionId);

        if (!$region) {
            return [
                'status' => 404,
                'message' => 'Region not found'
            ];
        }

        return [
            'status' => 200,
            'data' => [
                'region' => new RegionResource($region),
                'cities' => CityResource::collection($region->cities),
                'districts' => DistrictResource::collection($region->districts),
                'neighborhoods' => NeighborhoodResource::collection(
                    $region->districts->flatMap->neighborhoods
                ),
            ]
        ];
    }

    private function getDataByCity(int $cityId): array
    {
        $city = City::where('status', City::ACTIVE)
            ->with(['neighborhoods' => function ($query) {
                $query->where('status', Neighborhood::ACTIVE)
                    ->select('id', 'name', 'district_id', 'city_id');
            }])
            ->select('id', 'name')
            ->find($cityId);

        if (!$city) {
            return [
                'status' => 404,
                'message' => 'City not found'
            ];
        }

        $districts = District::where('status', District::ACTIVE)
            ->select('id', 'name')
            ->get();

        return [
            'status' => 200,
            'data' => [
                'city' => new CityResource($city),
                'districts' => DistrictResource::collection($districts),
                'neighborhoods' => NeighborhoodResource::collection(
                    $city->neighborhoods
                ),
            ]
        ];
    }

    private function getDataByDistrict(int $districtId): array
    {
        $district = District::where('status', District::ACTIVE)
            ->with(['neighborhoods' => function ($query) {
                $query->where('status', Neighborhood::ACTIVE)
                    ->select('id', 'name', 'district_id');
            }])
            ->select('id', 'name')
            ->find($districtId);

        if (!$district) {
            return [
                'status' => 404,
                'message' => 'District not found'
            ];
        }

        $cities = City::where('status', City::ACTIVE)
            ->select('id', 'name')
            ->get();

        return [
            'status' => 200,
            'data' => [
                'district' => new DistrictResource($district),
                'cities' => CityResource::collection($cities),
                'neighborhoods' => NeighborhoodResource::collection($district->neighborhoods),
            ]
        ];
    }

    private function getAllLocationData(): array
    {
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

        return [
            'status' => 200,
            'data' => [
                'regions' => RegionResource::collection($regions),
                'cities' => CityResource::collection($cities),
                'districts' => DistrictResource::collection($districts),
                'neighborhoods' => NeighborhoodResource::collection($neighborhoods),
            ]
        ];
    }
}
