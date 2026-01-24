<?php

namespace App\Enums;

enum ScheduledTaskStatus
{
    case PENDING;
    case RUNNING;
    case COMPLETED;
    case FAILED;
}
