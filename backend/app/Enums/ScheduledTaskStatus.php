<?php

namespace App\Enums;

enum ScheduledTaskStatus
{
    case PENDING_AUTO;
    case PENDING_MANUAL;
    case RUNNING;
    case COMPLETED;
    case FAILED;
}
