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
            'city' => $this->city->name,
            'district' => $this->district->name,
            'neighborhood' => $this->neighborhood->name,
            'home' => $this->home,
        ];
    }
}
