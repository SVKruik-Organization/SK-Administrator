<?php

namespace App\Enums;

enum VerificationReason
{
    case MFA;
    case SECURITY;
}
