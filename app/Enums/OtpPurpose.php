<?php

declare(strict_types=1);

namespace App\Enums;

/**
 * An OTP is bound to its purpose (M03 §3). The purpose is part of the lookup,
 * so a code issued to sign in can never satisfy a second-factor challenge, and
 * a code issued to verify a mobile number can do neither.
 */
enum OtpPurpose: string
{
    case MobileVerify = 'mobile_verify';
    case Login = 'login';
    case TwoFactor = 'two_factor';
}
