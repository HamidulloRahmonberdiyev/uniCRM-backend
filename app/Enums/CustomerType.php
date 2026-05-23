<?php

namespace App\Enums;

enum CustomerType: int
{
    case ACTIVE = 1;
    case NORMAL = 2;
    case PASSIVE = 3;

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    public static function labels(): array
    {
        return [
            self::ACTIVE->value    => 'Faol mijoz',
            self::NORMAL->value     => 'Normal mijoz',
            self::PASSIVE->value   => 'Passiv mijoz',
        ];
    }
}
