<?php

namespace App\Enums;

enum CompletionStatus: string
{
    case NOT_STARTED = 'not_started';

    case IN_PROGRESS = 'in_progress';

    case COMPLETED = 'completed';

    case FAILED = 'failed';
}
