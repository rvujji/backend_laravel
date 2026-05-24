<?php

namespace App\Enums;

enum SessionKind: string
{
    case ORIENTATION = 'orientation';

    case THEORY = 'theory';

    case LAB = 'lab';

    case PRACTICE = 'practice';

    case QA = 'qa';

    case ASSESSMENT = 'assessment';

    case DEMO_DAY = 'demo_day';

    case REVIEW = 'review';
}
