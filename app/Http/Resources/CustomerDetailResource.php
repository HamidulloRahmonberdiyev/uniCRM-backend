<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CustomerDetailResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'city' => new CityResource($this->city),
            'district' => new DistrictResource($this->district),
            'neighborhood' => new NeighborhoodResource($this->neighborhood),
            'home' => $this->home,
        ];
    }
}
