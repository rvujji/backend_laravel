<?php

namespace App\Enums;

enum SessionStatus: string
{
    case SCHEDULED = 'scheduled';

    case LIVE = 'live';

    case COMPLETED = 'completed';

    case CANCELLED = 'cancelled';

    case POSTPONED = 'postponed';
}
