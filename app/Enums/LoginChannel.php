<?php

declare(strict_types=1);

namespace App\Enums;

enum LoginChannel: string
{
    case Password = 'password';
    case Otp = 'otp';
}
