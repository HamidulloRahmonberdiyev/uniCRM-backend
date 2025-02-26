<?php

namespace App\Http\Controllers\Api\Settings;

use App\Http\Controllers\Controller;
use App\Http\Requests\Regions\FilterRegionRequest;
use App\Http\Resources\RegionDetailResource;
use App\Models\Region;
use App\Services\Location\LocationService;
use App\Traits\ApiJsonResponceTrait;
use Illuminate\Http\Request;

class RegionController extends Controller
{
    use ApiJsonResponceTrait;

    protected $locationService;

    public function __construct(LocationService $locationService)
    {
        $this->locationService = $locationService;
    }

    public function index(FilterRegionRequest $request)
    {
        return $this->locationService->getLocations($request);
    }

    public function region_details()
    {
        $regions = Region::with(['districts'])->get();

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
