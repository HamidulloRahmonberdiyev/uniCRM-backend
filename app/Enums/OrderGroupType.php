<?php

namespace App\Enums;

enum OrderGroupType: string
{
    case IN_PROGRESS = 'in_progress';
    case DELIVERING = 'delivering';
    case DELIVERED = 'delivered';
    case CANCELLED = 'cancelled';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    public function getConditions(): array
    {
        return match ($this) {
            self::IN_PROGRESS => ['status' => 1, 'supplier_condition' => 'null'],
            self::DELIVERING => ['status' => 1, 'supplier_condition' => 'not_null'],
            self::DELIVERED => ['status' => 3, 'supplier_condition' => 'any'],
            self::CANCELLED => ['status' => 2, 'supplier_condition' => 'any'],
        };
    }
}
