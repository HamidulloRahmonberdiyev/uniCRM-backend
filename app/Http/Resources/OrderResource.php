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
                'phone' => formatPhone($this->customer->phone),
                'phone2' => formatPhone($this->customer->phone2),
                'district' => optional(optional($this->customer->customerDetail)->district)->name,
                'neighborhood' => optional(optional($this->customer->customerDetail)->neighborhood)->name,
                'home' => optional($this->customer->customerDetail)->home,
            ] : null,
            'user' => $this->user->name,
            'district' => $this->district ? new DistrictResource($this->district) : null,
            'neighborhood' => $this->neighborhood ? new NeighborhoodResource($this->neighborhood) : null,
            'quantity' => $this->quantity,
            'sum' => $this->sum ?? null,
            'date' => formatDate($this->date),
            'address' => $this->address,
            'note' => $this->note,
            'lotitude' => $this->latitude,
            'longitude' => $this->longitude,
            'status' => $this->status,
            'source' => $this->source->name ?? null,
            'created_at' => formatDateTime($this->created_at),
        ];
    }
}
