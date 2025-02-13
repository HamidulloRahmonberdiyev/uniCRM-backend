<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RegionDetailResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'status' => $this->status,
            'cities' => $this->cities ? CityResource::collection($this->cities) : null,
            'districts' => $this->districts ? DistrictResource::collection($this->districts) : null,
        ];
    }
}
