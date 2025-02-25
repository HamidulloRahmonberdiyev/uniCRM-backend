<?php

namespace App\Enums;

enum CustomerType: int
{
    case ACTIVE = 1;
    case NORMAL = 2;
    case PASSIVE = 3;
}
