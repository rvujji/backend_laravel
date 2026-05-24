<?php

namespace App\Enums;

enum SessionReservationStatus: string
{
    case RESERVED = 'reserved';

    case CANCELLED = 'cancelled';

    case ATTENDED = 'attended';

    case MISSED = 'missed';

    case WAITLISTED = 'waitlisted';
}
