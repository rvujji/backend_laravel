<?php

namespace App\Enums;

enum CapacityMode: string
{
    case OFFERING_ONLY = 'offering_only';

    case SESSION_ONLY = 'session_only';

    case BOTH = 'both';
}
