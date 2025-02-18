<?php

namespace App\Http\Resources\Orders;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderDetailResource extends JsonResource
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
            ] : null,
            'city' => $this->city ? $this->city->name : null,
            'district' => $this->district ? $this->district->name : null,
            'neighborhood' => $this->neighborhood ? $this->neighborhood->name : null,
            'quantity' => $this->quantity,
            'sum' => $this->sum ?? null,
            'address' => $this->address,
            'lotitude' => $this->lotitude,
            'longitude' => $this->longitude,
            'status' => $this->status,
            'created_at' => formatDateTime($this->created_at),
        ];
    }
}
