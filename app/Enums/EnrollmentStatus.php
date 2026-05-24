<?php

namespace App\Enums;

enum EnrollmentStatus: string
{
    case PENDING = 'pending';

    case ACTIVE = 'active';

    case CANCELLED = 'cancelled';

    case COMPLETED = 'completed';

    case EXPIRED = 'expired';
}
