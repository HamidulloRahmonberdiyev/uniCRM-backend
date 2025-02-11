<?php

namespace App\Http\Resources;

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
            'date_of_birth' => formatDate($this->date_of_birth),
            'phone' => formatPhone($this->phone),
            'phone2' => formatPhone($this->phone2),
            'status' => $this->status,
            'type' => $this->type?->color,
            'customer_detail' => $this->customerDetail ? new CustomerDetailResource($this->customerDetail) : null,
        ];
    }
}
