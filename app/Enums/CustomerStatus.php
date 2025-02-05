<?php

use App\Enums\CustomerStatus;

enum CustomerStatus: string
{
    case ACTIVE = 'ACTIVE';
    case NORMAL = 'NORMAL';
    case PASSIVE = 'PASSIVE';
}
