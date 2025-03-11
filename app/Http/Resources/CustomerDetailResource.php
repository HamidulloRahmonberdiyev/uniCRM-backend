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
            'district' => $this->district ? new DistrictResource($this->district) : null,
            'neighborhood' => $this->neighborhood ? new NeighborhoodResource($this->neighborhood) : null,
            'home' => $this->home,
            'bottle_count' => $this->bottle_count,
        ];
    }
}
