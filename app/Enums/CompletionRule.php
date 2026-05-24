<?php

namespace App\Enums;

enum CompletionRule: string
{
    case ATTEND_ALL_REQUIRED = 'attend_all_required';

    case ATTEND_N_SESSIONS = 'attend_n_sessions';

    case ATTENDANCE_PERCENTAGE = 'attendance_percentage';

    case MANUAL_COMPLETION = 'manual_completion';
}
