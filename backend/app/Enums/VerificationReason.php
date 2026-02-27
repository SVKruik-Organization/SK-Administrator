<?php

declare(strict_types=1);

namespace App\Enums;

enum VerificationReason
{
    case MFA;
    case SECURITY;
}
