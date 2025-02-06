<?php

namespace App\Enums;

enum CustomerType: string
{
    case ACTIVE = 'ACTIVE';
    case NORMAL = 'NORMAL';
    case PASSIVE = 'PASSIVE';

    public static function valueOf($value): ?CustomerType
    {
        if ($value === null) return null;
        if ($value instanceof self) return $value;
        foreach (self::cases() as $case) {
            if ($case->value === $value) return $case;
        }
        return null;
    }
}
