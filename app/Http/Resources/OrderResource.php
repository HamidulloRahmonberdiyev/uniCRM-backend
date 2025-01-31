<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'customer' => new CustomerResource($this->whenLoaded('customer')),
            'user' => new UserResource($this->whenLoaded('user')),
            'company' => new CompanyResource($this->whenLoaded('company')),
            'city' => new CityResource($this->whenLoaded('city')),
            'district' => new DistrictResource($this->whenLoaded('district')),
            'neighborhood' => new NeighborhoodResource($this->whenLoaded('neighborhood')),
            'quantity' => $this->quantity,
            'sum' => $this->sum,
            'date' => $this->date->format('Y-m-d'),
            'address' => $this->address,
            'note' => $this->note,
            'location' => $this->location,
            'status' => (bool) $this->status,
        ];
    }
}
