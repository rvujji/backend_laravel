<?php

namespace App\Enums;

enum SessionSelectionRule: string
{
    case ALL_SESSIONS = 'all_sessions';

    case ANY_N_OF_M = 'any_n_of_m';

    case OPTIONAL_SESSIONS = 'optional_sessions';

    case SPECIFIC_TRACK_ONLY = 'specific_track_only';
}
