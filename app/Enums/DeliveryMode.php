<?php

namespace App\Enums;

enum DeliveryMode: string
{
    case PHYSICAL = 'physical';
    case VIRTUAL = 'virtual';
    case HYBRID = 'hybrid';
}
