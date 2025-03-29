<?php

namespace App\Http\Controllers\Api\Settings;

use App\Http\Controllers\Controller;
use App\Http\Requests\Neighborhood\FilterNeighborhoodRequest;
use App\Http\Requests\Neighborhood\StoreNeighborhoodRequest;
use App\Http\Requests\Neighborhood\UpdateNeighborhoodRequest;
use App\Http\Resources\NeighborhoodResource;
use App\Models\Neighborhood;
use App\Services\Location\NeighborhoodService;
use App\Traits\ApiJsonResponceTrait;

class NeighborhoodController extends Controller
{
    use ApiJsonResponceTrait;

    public function __construct(protected NeighborhoodService $neighborhoodService) {}

    public function index(FilterNeighborhoodRequest $request)
    {
        $neighborhoods = $this->neighborhoodService->getNeighborhoods($request->validated());

        return NeighborhoodResource::collection($neighborhoods);
    }

    public function store(StoreNeighborhoodRequest $request)
    {
        $neighborhood = $this->neighborhoodService->createNeighborhood($request->validated());

        return new NeighborhoodResource($neighborhood);
    }

    public function update(UpdateNeighborhoodRequest $request, Neighborhood $neighborhood)
    {
        $neighborhood = $this->neighborhoodService->updateNeighborhood($request->validated(), $neighborhood);

        return new NeighborhoodResource($neighborhood);
    }

    public function destroy(Neighborhood $neighborhood)
    {
        $neighborhood->delete();

        return $this->successResponse('Neighborhood deleted successfully');
    }
}
