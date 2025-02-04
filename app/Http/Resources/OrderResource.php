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
            'user' => $this->user->name,
            'city' => $this->city->name ?? null,
            'district' => $this->district->name ?? null,
            'neighborhood' => $this->neighborhood->name ?? null,
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
