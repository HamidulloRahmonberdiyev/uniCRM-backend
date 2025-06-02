<?php

namespace App\Http\Requests\Monitoring;

use App\Enums\OrderGroupType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Enum;

class OrderMonitoringRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules()
    {
        return [
            'page' => 'nullable|integer|min:1',
            'supplier_id' => 'nullable|integer|exists:users,id',
            'group_type' => ['nullable', 'string', Rule::enum(OrderGroupType::class)]
        ];
    }

    public function getPage(): int
    {
        return max(1, (int)$this->input('page', 1));
    }

    public function getPerPage(): int
    {
        return 15;
    }

    public function getGroupType(): ?OrderGroupType
    {
        return OrderGroupType::tryFrom($this->input('group_type'));
    }

    public function getSupplierId(): ?int
    {
        $supplierId = $this->input('supplier_id');
        return $supplierId !== null ? (int)$supplierId : null;
    }
}
