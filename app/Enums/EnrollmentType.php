<?php

namespace App\Enums;

enum EnrollmentType: string
{
    case FULL_SERIES = 'full_series';

    case SESSION_SELECTION = 'session_selection';

    case DROP_IN = 'drop_in';

    case SUBSCRIPTION_ACCESS = 'subscription_access';
}
