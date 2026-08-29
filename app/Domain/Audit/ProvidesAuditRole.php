<?php

declare(strict_types=1);

namespace App\Domain\Audit;

/**
 * An actor that can describe the authority it acted under.
 *
 * Every audit entry records the role in effect, including its organisational
 * scope (M26 §2) -- "who did this" is not the same question as "what were they
 * entitled to do", and a service appeal six years later asks the second one.
 */
interface ProvidesAuditRole
{
    public function auditRole(): string;
}
