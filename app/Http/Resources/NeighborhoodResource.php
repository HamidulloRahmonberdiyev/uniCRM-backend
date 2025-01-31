<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class NeighborhoodResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'city_id' => $this->region_id,
            'district_id' => $this->region_id,
            'name' => $this->name,
            'second_name' => $this->name,
            'status' => $this->region_id,
        ];
    }
}
