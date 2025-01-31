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
            'customer' => new CustomerResource($this->customer),
            'user' => new UserResource($this->user),
            'company' => new CompanyResource($this->company),
            'city' => new CityResource($this->city),
            'district' => new DistrictResource($this->district),
            'neighborhood' => new NeighborhoodResource($this->neighborhood),
            'quantity' => $this->quantity,
            'sum' => $this->sum,
            'date' => $this->date->format('Y-m-d'),
            'address' => $this->address,
            'note' => $this->note,
            'location' => $this->location,
            'status' => (bool) $this->status,
            'updated_at' => $this->updated_at->format('Y-m-d H:i:s'),
        ];
    }
}
