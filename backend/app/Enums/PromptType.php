<?php

declare(strict_types=1);

namespace App\Enums;

enum PromptType
{
    case INFO;
    case SUCCESS;
    case WARNING;
    case DANGER;
}
