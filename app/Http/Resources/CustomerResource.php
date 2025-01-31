<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CustomerResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'company_id' => $this->company_id,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'middle_name' => $this->middle_name,
            'date_of_birth' => $this->date_of_birth->format('Y-m-d'),
            'phone' => $this->phone,
            'phone2' => $this->phone2,
            'status' => $this->status,
            'customer_details' => CustomerDetailResource::collection($this->customerDetails),
        ];
    }
}
