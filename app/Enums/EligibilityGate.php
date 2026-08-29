<?php

declare(strict_types=1);

namespace App\Enums;

enum EligibilityGate: string
{
    case Scrutiny = 'scrutiny';
    case WrittenTest = 'written_test';
    case Interview = 'interview';

    public function label(): string
    {
        return match ($this) {
            self::Scrutiny => __('application.gate_scrutiny'),
            self::WrittenTest => __('application.gate_written_test'),
            self::Interview => __('application.gate_interview'),
        };
    }
}
