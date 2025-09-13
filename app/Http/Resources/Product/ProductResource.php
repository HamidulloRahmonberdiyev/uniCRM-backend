<?php

namespace App\Http\Resources\Product;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'price' => formatPrice($this->price),
            'image' => ($this->image ? 'storage/' . $this->image : null),
            'status' => $this->status,
        ];
    }
}
