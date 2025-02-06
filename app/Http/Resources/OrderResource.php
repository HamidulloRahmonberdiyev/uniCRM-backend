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
            'customer' => $this->customer ? [
                'first_name' => $this->customer->first_name,
                'last_name' => $this->customer->last_name,
                'middle_name' => $this->customer->middle_name,
                'phone' => $this->customer->phone,
                'phone2' => $this->customer->phone2,
            ] : null,
            'user' => $this->user->name,
            'city' => $this->city ? new CityResource($this->city) : null,
            'district' => $this->district ? new DistrictResource($this->district) : null,
            'neighborhood' => $this->neighborhood ? new NeighborhoodResource($this->neighborhood) : null,
            'quantity' => $this->quantity,
            'sum' => $this->sum ?? null,
            'date' => formatDate($this->date),
            'address' => $this->address,
            'note' => $this->note,
            'location' => $this->location,
            'status' => $this->status,
            'created_at' => formatDateTime($this->created_at),
        ];
    }
}
