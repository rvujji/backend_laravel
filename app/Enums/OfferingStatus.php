<?php

namespace App\Enums;

enum OfferingStatus: string
{
    case DRAFT = 'draft';

    case PUBLISHED = 'published';

    case ONGOING = 'ongoing';

    case COMPLETED = 'completed';

    case CANCELLED = 'cancelled';

    case ARCHIVED = 'archived';
}
