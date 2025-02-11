<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ReturnedResource extends JsonResource
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
            'user' => $this->user->name,
            'quantity' => $this->quantity,
            'date' => formatDate($this->date),
        ];
    }
}
