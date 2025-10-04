<?php

namespace App\Http\Resources\Sms;

use App\Enums\Sms\SmsTemplateTypeEnum;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SmsTemplateResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'content' => $this->content,
            'type' => [
                'value' => $this->type->value,
                'label' => SmsTemplateTypeEnum::labels()[$this->type->value],
            ],
            'status' => (bool) $this->status,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
