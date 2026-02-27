<?php

declare(strict_types=1);

namespace App\Enums;

enum ScheduledTaskType
{
    case MAINTENANCE;
    case BACKUP;
    case REPORT;
    case NOTIFICATION;
    case OTHER;
}
