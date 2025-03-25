<?php

namespace App\Http\Resources;

use App\Models\Order;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'username' => $this->username,
            'phone' => formatPhone($this->phone),
            'roles' => $this->roles ? RoleResource::collection($this->roles) : null,
            'role_name' => $this->role_name,
            'created_at' => $this->created_at,
            'orders_count' => Order::where('supplier_id', $this->id)->where('status', Order::DONE)->count(),
            'status' => $this->status,
        ];
    }
}
